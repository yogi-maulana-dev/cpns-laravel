<?php

namespace App\Imports;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantsImport implements ToCollection, WithStartRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Participant::create([
                'nik' => $row[0],
                'email' => $row[1],
                'name' => $row[2],
                'address' => $row[3],
                'place_of_birth' => $row[4],
                'date_of_birth' => $row[5],
                'gender' => $row[6] === 'L' ? 0 : 1,
                'phone_number' => $row[7],
            ]);
        }
    }

    public function startRow(): int
    {
        return 3;
    }

    public function prepareForValidation($data, $index)
    {
        $data[5] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[5])->format('Y-m-d');

        return $data;
    }

    public function rules(): array
    {
        return [
            '*.0' => ['required', 'digits:16', Rule::unique('participants', 'nik')], // nik
            '*.1' => ['required', 'email', Rule::unique('participants', 'email'), Rule::unique('users', 'email')], // email
            '*.2' => ['required', 'max:50'], // name
            '*.3' => ['required', 'max:128'], // address
            '*.4' => ['required', 'max:128'], // place_of_birth
            '*.5' => ['required', 'date'], // date_of_birth
            '*.6' => ['required', 'in:L,P'], // gender
            '*.7' => ['required', 'max:25'], // phone_number
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '*.0' => 'NIK',
            '*.1' => 'Alamat Email',
            '*.2' => 'Nama',
            '*.3' => 'Alamat',
            '*.4' => 'Tempat Lahir',
            '*.5' => 'Tanggal Lahir',
            '*.6' => 'Jenis Kelamin (L/P)',
            '*.7' => 'Nomor Telepon',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validateWithBag('import');
    }
}
