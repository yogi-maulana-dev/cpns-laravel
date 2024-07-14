<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $questions)
    {
    }

    public function collection()
    {
        return $this->questions;
    }

    public function map($question): array
    {
        return [
            $question->id,
            $question->question_text,
            $question->discussion,
            $question->questionType->name,
            chr(64 + $question->order_index_correct_answer),
            $question?->answers[0]?->answer_text,
            $question?->answers[0]?->weight_score,
            $question?->answers[1]?->answer_text,
            $question?->answers[1]?->weight_score,
            $question?->answers[2]?->answer_text,
            $question?->answers[2]?->weight_score,
            $question?->answers[3]?->answer_text,
            $question?->answers[3]?->weight_score,
            $question?->answers[4]?->answer_text,
            $question?->answers[4]?->weight_score,
            $question->created_at->format('d/m/Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            [
                'ID',
                'Isi Soal',
                'Pembahasan',
                'Tipe Soal',
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
                'Tanggal di Buat',
            ],
        ];
    }
}
