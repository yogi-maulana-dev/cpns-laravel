@props(['students'])

<div class="mb-3">
    <x-forms.label id="check_siswa">Pilih Peserta Didik</x-forms.label>
    @if($students->isNotEmpty())
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">
                        <div class="form-check d-flex align-items-center justify-content-center">
                            <input class="form-check-input" type="checkbox" value="" id="check-all">
                        </div>
                    </th>
                    <th scope="col" style="white-space: nowrap;">#/ID</th>
                    <th scope="col" style="white-space: nowrap;">NIS/NISN/NIK</th>
                    <th scope="col" style="white-space: nowrap;">Nama Lengkap</th>
                    <th scope="col" style="white-space: nowrap;">Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>
                        <div class="form-check d-flex align-items-center justify-content-center">
                            <input class="form-check-input form-check-input-student-ids" type="checkbox"
                                name="student_ids[]" value="{{ $student->id }}">
                        </div>
                    </td>
                    <th scope="row" style="white-space: nowrap;">{{ $loop->iteration }}/{{ $student->id }}</th>
                    <td>{{ $student->nis }}
                        <span class="fw-bold">/</span>
                        {{ $student->nisn }}
                        <span class="fw-bold">/</span>
                        {{ $student->nik }}
                    </td>
                    <td style="white-space: nowrap;">
                        <a target="_blank" href="{{ route('students.show', $student->id) }}">{{
                            $student->nama_lengkap }}</a>
                    </td>
                    <td style="white-space: nowrap;">{{ $student->class->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        @php
        echo $students->links();
        @endphp
    </div>
    @else
    <p class="fw-bold text-danger text-center py-5">Data siswa tidak ditemukan!</p>
    @endif
</div>