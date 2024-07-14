<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionTypeRequest extends FormRequest
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
        $questionTypeId = $this->route('question_type')?->id ?? null;

        return [
            'name' => ['required', 'max:32', Rule::unique('question_types', 'name')->ignore($questionTypeId, 'id')],
            'question_group_type_id' => ['required', 'numeric'],
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
            'name' => 'Nama Tipe Soal',
            'question_group_type_id' => 'Tipe Kelompok Soal',
        ];
    }
}
