<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError; // Pour sauter les erreurs et continuer
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // Pour sauter les échecs de validation et continuer
use Maatwebsite\Excel\Validators\Failure; // Pour capturer les échecs
use Throwable; // Pour capturer les erreurs générales
use Illuminate\Support\Facades\Log; // Pour logger les erreurs

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $importedRowCount = 0;
    private $skippedRows = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Vérifier si l'email existe déjà pour éviter les doublons
        // La règle 'unique' dans rules() devrait aussi gérer cela, mais une vérification ici est une sécurité
        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            $this->skippedRows[] = ['row_data' => $row, 'reason' => 'Email déjà existant.'];
            Log::warning("Importation Excel : Email déjà existant ignoré - " . $row['email']);
            return null; // Ne pas créer l'utilisateur
        }

        $this->importedRowCount++;

        $user = new User([
            'name'     => $row['nom_complet'], // Doit correspondre aux en-têtes de votre Excel
            'email'    => $row['email'],
            'password' => Hash::make($row['mot_de_passe_initial'] ?? 'password123'), // Mot de passe par défaut si non fourni
            'telephone' => $row['telephone'] ?? null,
            'post'      => $row['poste'] ?? null,
            'statut'    => strtolower($row['statut'] ?? 'actif'), // Assurer que c'est en minuscules
            'date_embauche' => isset($row['date_dembauche']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_dembauche'])->format('Y-m-d') : null,
            'must_change_password' => true, // Toujours vrai pour les nouveaux importés
            'theme' => 'light', // Thème par défaut
        ]);

        // Sauvegarder l'utilisateur AVANT d'assigner le rôle
        // $user->save(); // La méthode ToModel s'attend à ce que vous retourniez un modèle non sauvegardé, il sera sauvegardé par le package.

        return $user; // Le package sauvegardera le modèle et appellera 'assignRole' si c'est un événement après la création
                      // Pour plus de contrôle, vous pouvez utiliser `WithBatchInserts` et `WithUpserts` ou `ToCollection`
                      // et gérer la sauvegarde et l'assignation de rôle vous-même.
                      // Pour l'instant, on va supposer que vous assignerez le rôle dans le contrôleur après l'import.
                      // Alternative: si vous utilisez ToCollection, vous pouvez faire:
                      // $user->save();
                      // $user->assignRole('employe'); // directement ici
    }

    /**
     * Définir les règles de validation pour chaque ligne.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Unique pour éviter les doublons
            'mot_de_passe_initial' => 'nullable|string|min:8', // Mot de passe optionnel dans le fichier Excel
            'telephone' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'statut' => 'nullable|string|in:actif,inactif,en_conge,Actif,Inactif,En_Conge', // Accepter différentes casses
            'date_dembauche' => 'nullable|numeric', // Excel stocke les dates comme des nombres
        ];
    }

    /**
     * Personnaliser les messages de validation.
     *
     * @return array
     */
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

    /**
     * Gérer les erreurs de validation (ne saute pas la ligne, l'erreur est capturée par le contrôleur).
     * Si vous voulez sauter la ligne, utilisez plutôt onError.
     *
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Stocker les échecs pour les afficher à l'utilisateur
        // Ils seront également disponibles via $e->failures() dans le bloc catch du contrôleur
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

    /**
     * Gérer les erreurs générales (autres que la validation).
     *
     * @param Throwable $e
     */
    public function onError(Throwable $e)
    {
        // Logger l'erreur mais continuer l'importation des autres lignes
        Log::error("Erreur d'importation Excel (onError): " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
        // Vous pourriez vouloir stocker cette erreur aussi pour l'afficher.
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
