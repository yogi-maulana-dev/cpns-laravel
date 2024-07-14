<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToCollection, WithStartRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $question = Question::create([
                'question_text' => $row[0],
                'discussion' => $row[1],
                'question_type_id' => $row[2],
                'order_index_correct_answer' => $row[3],
            ]);
            $question->answers()->createMany([
                [
                    'answer_text' => $row[4],
                    'weight_score' => $row[5],
                    'order_index' => 1,
                ],
                [
                    'answer_text' => $row[6],
                    'weight_score' => $row[7],
                    'order_index' => 2,
                ],
                [
                    'answer_text' => $row[8],
                    'weight_score' => $row[9],
                    'order_index' => 3,
                ],
                [
                    'answer_text' => $row[10],
                    'weight_score' => $row[11],
                    'order_index' => 4,
                ],
                [
                    'answer_text' => $row[12],
                    'weight_score' => $row[13],
                    'order_index' => 5,
                ],
            ]);
        }
    }

    public function startRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            '*.0' => ['required', 'max:2048'], // question_text
            '*.1' => ['max:2048'], // discussion
            '*.2' => ['required', 'numeric'], // question_type_id
            '*.3' => ['required', 'numeric', 'in:1,2,3,4,5'], // order_index_correct_answer
            '*.4' => ['required', 'max:1024'], // answers.A.answer_text
            '*.5' => ['required', 'numeric', 'min:0', 'max:245'], // answers.A.weight_score
            '*.6' => ['required', 'max:1024'], // answers.B.answer_text
            '*.7' => ['required', 'numeric', 'min:0', 'max:245'], // answers.B.weight_score
            '*.8' => ['required', 'max:1024'], // answers.C.answer_text
            '*.9' => ['required', 'numeric', 'min:0', 'max:245'], // answers.C.weight_score
            '*.10' => ['required', 'max:1024'], // answers.D.answer_text
            '*.11' => ['required', 'numeric', 'min:0', 'max:245'], // answers.D.weight_score
            '*.12' => ['required', 'max:1024'], // answers.E.answer_text
            '*.13' => ['required', 'numeric', 'min:0', 'max:245'], // answers.E.weight_score
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '*.0' => 'Isi Soal',
            '*.1' => 'Pembahasan',
            '*.2' => 'ID Tipe Soal',
            '*.3' => 'Jawaban Benar',
            '*.4' => 'Isi Jawaban A',
            '*.5' => 'Bobot Nilai Jawaban A',
            '*.6' => 'Isi Jawaban B',
            '*.7' => 'Bobot Nilai Jawaban B',
            '*.8' => 'Isi Jawaban C',
            '*.9' => 'Bobot Nilai Jawaban C',
            '*.10' => 'Isi Jawaban D',
            '*.11' => 'Bobot Nilai Jawaban D',
            '*.12' => 'Isi Jawaban E',
            '*.13' => 'Bobot Nilai Jawaban E',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validateWithBag('import');
    }
}
