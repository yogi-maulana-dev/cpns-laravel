<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamSessionSettingRequest extends FormRequest
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
            'question_group_type_id' => ['required', 'numeric'],
            'number_of_question' => ['required', 'min:0'],
            'passing_grade' => ['required', 'min:0'],
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
            'question_group_type_id' => 'Tipe Kelompok Soal',
            'number_of_question' => 'Jumlah Soal',
            'passing_grade' => 'Passing Grade',
        ];
    }
}
