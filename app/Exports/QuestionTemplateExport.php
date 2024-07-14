<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionTemplateExport implements ShouldAutoSize, WithHeadings
{
    public function headings(): array
    {
        return [
            [
                '',
                '',
                '* Masukan ID Dari Data Tipe Soal',
                '* Masukan Urutan Jawaban Benar, 1 Untuk A, 2 Untuk B, 3 Untuk C, 4 Untuk D, 5 Untuk E',
                '',
                '* Minimal 0 Sampai 245',
                '',
                '* Minimal 0 Sampai 245',
                '',
                '* Minimal 0 Sampai 245',
                '',
                '* Minimal 0 Sampai 245',
                '',
                '* Minimal 0 Sampai 245',
            ],
            [
                'Isi Soal',
                'Pembahasan',
                'ID Tipe Soal',
                'Jawaban Benar',
                'Isi Jawaban A',
                'Bobot Nilai Jawaban A',
                'Isi Jawaban B',
                'Bobot Nilai Jawaban B',
                'Isi Jawaban C',
                'Bobot Nilai Jawaban C',
                'Isi Jawaban D',
                'Bobot Nilai Jawaban D',
                'Isi Jawaban E',
                'Bobot Nilai Jawaban E',
            ],
        ];
    }
}
