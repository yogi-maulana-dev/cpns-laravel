<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParticipantRequest extends FormRequest
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
        $participantId = $this->route('participant')?->id ?? null;

        return [
            'nik' => ['required', 'numeric', 'digits:13', Rule::unique('participants', 'nik')->ignore($participantId, 'id')],
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:participants,email', Rule::unique('users', 'email')],
            'address' => ['required', 'max:128'],
            'place_of_birth' => ['required', 'max:128'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'boolean'],
            'phone_number' => ['required', 'max:25'],
            'picture' => ['sometimes', 'nullable', 'image', 'max:512'],
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
            'nik' => 'Nomor Peserta',
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'address' => 'Alamat',
            'place_of_birth' => 'Tempat Lahir',
            'date_of_birth' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'phone_number' => 'No. Telepon',
            'picture' => 'Foto',
        ];
    }
}
