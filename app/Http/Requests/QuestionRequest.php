<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'question_text' => ['required', 'max:2048'],
            'question_image' => ['sometimes', 'nullable', 'image', 'max:512'],
            'discussion_image' => ['sometimes', 'nullable', 'image', 'max:512'],
            'discussion' => ['max:2048'],
            'question_type_id' => ['required', 'numeric'],
            'order_index_correct_answer' => ['required', 'numeric', 'in:1,2,3,4,5'],
            'answers' => ['required', 'array'],
            'answers.*.answer_text' => ['required', 'max:1024'],
            'answers.*.answer_image' => ['sometimes', 'nullable', 'image', 'max:512'],
            'answers.*.weight_score' => ['required', 'numeric', 'min:0', 'max:245'],
            'answers.*.order_index' => ['required', 'numeric', 'in:1,2,3,4,5'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'question_text' => 'Isi Soal',
            'question_image' => 'Gambar Soal',
            'discussion_image' => 'Gambar Pembahasan',
            'discussion' => 'Pembahasan',
            'question_type_id' => 'Tipe Soal',
            'answers.*.answer_text' => 'Isi Jawaban',
            'answers.*.answer_image' => 'Gambar Jawaban',
            'answers.*.weight_score' => 'Bobot Nilai Jawaban',
            'order_index_correct_answer' => 'Jawaban Benar',
        ];
    }
}
