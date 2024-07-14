<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantTemplateExport implements ShouldAutoSize, WithHeadings
{
    public function headings(): array
    {
        return [
            [
                '* NIK harus 16 digit dan harus berbeda setiap data (Selalu awali dengan petik \')',
                '* Unik',
                '*',
                '*',
                '*',
                '* Gunakan fitur tanggal Excel',
                '* L / P',
                '*',
            ],
            [
                'NIK',
                'Alamat Email',
                'Nama',
                'Alamat',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin (L/P)',
                'Nomor Telepon',
            ],
        ];
    }
}
