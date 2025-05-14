<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Spatie\Permission\Models\Role;
use Throwable;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $importedRowCount = 0;
    private $skippedRowsLog = [];
    private $createdUserIds = [];
    private $employeRole;

    public function __construct() {
        $this->employeRole = Role::firstOrCreate(['name' => 'employe']);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $rowIndex => $row)
        {
            $email = trim($row['email'] ?? '');
            if (empty($email)) {
                $this->skippedRowsLog[] = ['row' => $rowIndex + 2, 'email' => '(Vide)', 'reason' => 'Email manquant.'];
                Log::warning("Importation Excel : Email manquant ignoré à la ligne " . ($rowIndex + 2));
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $this->skippedRowsLog[] = ['row' => $rowIndex + 2, 'email' => $email, 'reason' => 'Email déjà existant.'];
                Log::warning("Importation Excel : Email déjà existant ignoré à la ligne " . ($rowIndex + 2) . " - " . $email);
                continue;
            }

            try {
                $userData = [
                    'name'     => trim($row['nom_complet'] ?? ''),
                    'email'    => $email,
                    'password' => Hash::make(trim($row['mot_de_passe_initial'] ?? 'password123')),
                    'telephone' => trim($row['telephone'] ?? '') ?: null,
                    'post'      => trim($row['poste'] ?? '') ?: null,
                    'statut'    => strtolower(trim($row['statut'] ?? 'actif')),
                    'date_embauche' => isset($row['date_dembauche']) ? $this->transformDate($row['date_dembauche']) : null,
                    'must_change_password' => true,
                    'theme' => 'light',
                ];

                $user = User::create($userData);

                $roleToAssign = trim(strtolower($row['role'] ?? ''));
                if (!empty($roleToAssign)) {
                    $roleExists = Role::where('name', $roleToAssign)->first();
                    if ($roleExists && $roleExists->name !== 'directeur') { // Ne pas permettre d'assigner 'directeur' via import
                        $user->assignRole($roleExists);
                    } else {
                        $user->assignRole($this->employeRole); // Rôle 'employe' par défaut si le rôle fourni n'est pas valide ou est 'directeur'
                    }
                } else {
                    $user->assignRole($this->employeRole); // Rôle 'employe' par défaut si la colonne rôle est vide
                }

                $this->createdUserIds[] = $user->id;
                $this->importedRowCount++;

            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error("Erreur de validation (import) ligne " . ($rowIndex + 2) . ": " . json_encode($e->errors()));
                $this->skippedRowsLog[] = ['row' => $rowIndex + 2, 'email' => $email, 'reason' => 'Données invalides: ' . json_encode($e->errors())];
            } catch (Throwable $e) {
                Log::error("Erreur création utilisateur (import) ligne " . ($rowIndex + 2) . " email: " . $email . " - " . $e->getMessage());
                $this->skippedRowsLog[] = ['row' => $rowIndex + 2, 'email' => $email, 'reason' => 'Erreur système: ' . $e->getMessage()];
                $this->handleError($e);
            }
        }
    }

    protected function transformDate($value, $format = 'Y-m-d')
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format($format);
            }
            return \Carbon\Carbon::parse($value)->format($format);
        } catch (\Exception $e) {
            Log::warning("Échec de la conversion de date pour la valeur: " . $value . " - Erreur: " . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.nom_complet' => 'required|string|max:255',
            '*.email' => 'required|string|email|max:255|unique:users,email',
            '*.mot_de_passe_initial' => 'nullable|string|min:6', // Changé à min:6 pour être moins strict que min:8 si besoin
            '*.telephone' => 'nullable|string|max:20',
            '*.poste' => 'nullable|string|max:255',
            '*.statut' => 'nullable|string|in:actif,inactif,en_conge,Actif,Inactif,En_Conge,ACTIF,INACTIF,EN_CONGE',
            '*.date_dembauche' => 'nullable',
            '*.role' => 'nullable|string|exists:roles,name', // Valider que le rôle existe s'il est fourni
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nom_complet.required' => 'Le nom complet est requis (ligne :attribute).',
            '*.email.required' => 'L\'email est requis (ligne :attribute).',
            '*.email.email' => 'L\'email doit être valide (ligne :attribute).',
            '*.email.unique' => 'Cet email est déjà utilisé (ligne :attribute).',
            '*.statut.in' => 'Le statut n\'est pas valide à la ligne :attribute (attendu: actif, inactif, ou en_conge).',
            '*.role.exists' => 'Le rôle spécifié à la ligne :attribute n\'existe pas.',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedRowsLog[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
            Log::warning("Échec validation (onFailure) Ligne: " . $failure->row() . " Attribut: " . $failure->attribute() . " Erreurs: " . implode(', ', $failure->errors()));
        }
    }

    public function onError(Throwable $e)
    {
        Log::error("Erreur importation (onError): " . $e->getMessage() . "\n" . $e->getTraceAsString());
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }

    public function getSkippedRowsLog(): array
    {
        return $this->skippedRowsLog;
    }

    public function getCreatedUserIds(): array
    {
        return $this->createdUserIds;
    }
}
