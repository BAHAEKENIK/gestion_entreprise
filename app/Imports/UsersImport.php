<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $importedRowCount = 0;
    private $skippedRows = [];

    public function model(array $row)
    {
        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            $this->skippedRows[] = ['row_data' => $row, 'reason' => 'Email déjà existant.'];
            Log::warning("Importation Excel : Email déjà existant ignoré - " . $row['email']);
            return null;
        }

        $this->importedRowCount++;

        $user = new User([
            'name'     => $row['nom_complet'],
            'email'    => $row['email'],
            'password' => Hash::make($row['mot_de_passe_initial'] ?? 'password123'),
            'telephone' => $row['telephone'] ?? null,
            'post'      => $row['poste'] ?? null,
            'statut'    => strtolower($row['statut'] ?? 'actif'),
            'date_embauche' => isset($row['date_dembauche']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_dembauche'])->format('Y-m-d') : null,
            'must_change_password' => true,
            'theme' => 'light',
        ]);

        return $user;
    }

    public function rules(): array
    {
        return [
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'mot_de_passe_initial' => 'nullable|string|min:8',
            'telephone' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'statut' => 'nullable|string|in:actif,inactif,en_conge,Actif,Inactif,En_Conge',
            'date_dembauche' => 'nullable|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nom_complet.required' => 'Le nom complet est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'statut.in' => 'La valeur pour le statut n\'est pas valide (actif, inactif, en_conge).',
            'date_dembauche.numeric' => 'La date d\'embauche doit être un nombre Excel valide si fournie.',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
             $this->skippedRows[] = [
                'row_number' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
            Log::warning("Échec de validation importation Excel - Ligne: " . $failure->row() . " Attribut: " . $failure->attribute() . " Erreurs: " . implode(', ', $failure->errors()));
        }
    }

    public function onError(Throwable $e)
    {
        Log::error("Erreur d'importation Excel (onError): " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}
