<?php

namespace App\Http\Requests;

use App\Enums\OrderOfQuestion;
use App\Enums\ResultDisplayStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamSessionRequest extends FormRequest
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
            'name' => ['required', 'max:128'],
            'description' => ['sometimes', 'nullable', 'max:512'],
            'order_of_question' => ['required', Rule::in(OrderOfQuestion::values())],
            'time' => ['required', 'numeric', 'min:0'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_date'],
            'result_display_status' => ['required', Rule::in(ResultDisplayStatus::values())],
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
            'name' => 'Nama Sesi Ujian',
            'description' => 'Keterangan',
            'order_of_question' => 'Urutan Soal',
            'time' => 'Waktu',
            'start_at' => 'Tanggal & Waktu Mulai Ujian',
            'end_at' => 'Tanggal & Waktu Berakhir Ujian',
            'result_display_status' => 'Status Hasil Akhir Jawaban',
        ];
    }
}
