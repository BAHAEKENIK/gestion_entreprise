<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Exporter uniquement les utilisateurs qui ont le rôle 'employe'
        // et ne sont pas des directeurs. Ajustez la logique si nécessaire.
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'employe');
        })
        ->whereDoesntHave('roles', function ($query) { // Optionnel: pour être sûr
            $query->where('name', 'directeur');
        })
        ->orderBy('name')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nom Complet',
            'Email',
            'Téléphone',
            'Poste',
            'Statut',
            'Date d\'embauche',
            'Rôles', // Sera une chaîne des rôles
            // 'Thème', // Peut-être pas pertinent pour l'export de gestion
            // 'Doit changer mot de passe',
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->telephone,
            $user->post,
            ucfirst($user->statut),
            $user->date_embauche ? \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y') : '',
            $user->getRoleNames()->implode(', '), // Affiche les rôles séparés par une virgule
            // $user->theme,
            // $user->must_change_password ? 'Oui' : 'Non',
        ];
    }

    /**
     * Appliquer des styles à la feuille Excel.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de la première ligne (en-têtes)
            1    => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F4F4F'], // Gris foncé
                ],
            ],

            // Appliquer des bordures à toutes les cellules de données
            'A1:H' . ($this->collection()->count() + 1) => [ // Ajustez H si vous avez plus de colonnes
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}
