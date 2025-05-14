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
    public function collection()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'employe');
        })
        ->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'directeur');
        })
        ->orderBy('name')
        ->get();
    }

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
            'Rôles',
        ];
    }

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
            $user->getRoleNames()->implode(', '),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F4F4F'],
                ],
            ],
            'A1:H' . ($this->collection()->count() + 1) => [
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
