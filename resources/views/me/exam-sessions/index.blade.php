@extends('layouts.app')

@section('content')
<div class="row">
    @if($examSession)
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h2 class="fw-semibold">Biodata Peserta Ujian</h2>
                <hr class="mb-4">

                <div class="row">
                    <div class="col-sm-3">
                        <img src="{{ asset('storage/' . $participant->picture) }}" alt="Foto Peserta"
                            class="img-thumbnail d-block w-100" />
                    </div>
                    <div class="col-sm-9">
                        <small class="d-block fw-bold">Nomor Peserta</small>
                        <span class="text-muted mb-2 d-block">{{ $participant->nik }}</span>
                        <small class="d-block fw-bold">Nama Lengkap</small>
                        <span class="text-muted mb-2 d-block">{{ $participant->name }}</span>
                        <small class="d-block fw-bold">Tanggal Lahir</small>
                        <span class="text-muted mb-2 d-block">{{ $participant->date_of_birth->translatedFormat('d F
                            Y')
                            }}</span>
                        <small class="d-block fw-bold">Jenis Kelamin</small>
                        <span class="text-muted mb-2 d-block">{{ $participant->getGenderText() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2 class="fw-semibold">Detail Sesi Ujian</h2>
                <hr class="mb-4">

                <div>
                    <small class="d-block fw-bold">Nomor Sesi</small>
                    <span class="text-muted mb-2 d-block">{{ $examSession->code }}</span>
                    <small class="d-block fw-bold">Nama</small>
                    <span class="text-muted mb-2 d-block">{{ $examSession->name }}</span>
                    <small class="d-block fw-bold">Keterangan</small>
                    <span class="text-muted mb-2 d-block">{!! nl2br($examSession->description) !!}</span>
                    <small class="d-block fw-bold">Waktu</small>
                    <span class="text-muted mb-2 d-block">{{ $examSession->time }} Menit</span>
                    <small class="d-block fw-bold">Tanggal & Waktu Mulai <x-tooltip
                            title="Waktu dimana peserta dapat memulai ujian.">
                            <x-feather name="info" />
                        </x-tooltip></small>
                    <span class="text-muted mb-2 d-block">{{ $examSession->start_at->translatedFormat('d F
                        Y H:i')
                        }}</span>
                    <small class="d-block fw-bold">Tanggal & Waktu Berakhir
                        <x-tooltip title="Waktu dimana peserta tidak dapat lagi memulai ujian.">
                            <x-feather name="info" />
                        </x-tooltip>
                    </small>
                    <span class="text-muted mb-2 d-block">{{ $examSession->end_at->translatedFormat('d F
                        Y H:i')
                        }}</span>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="row">No</th>
                            <th>Tipe Kelompok Soal</th>
                            <th>Jumlah Soal</th>
                            <th>Passing Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($examSession->examSessionSettings as $setting)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                {{ $setting->questionGroupType->name }}
                            </td>
                            <td>
                                {{ $setting->number_of_question }}
                            </td>
                            <td>
                                {{ $setting->passing_grade }}
                            </td>
                        </tr>
                        @endforeach

                        @if ($examSession->examSessionSettings->isEmpty())
                        <tr>
                            <td colspan="4">
                                <p class="text-danger p-0 py-2 m-0 fw-bold text-center">Tidak ada data.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                @if($examSession->isOpen())
                <x-button as="a" color="dark" class="w-100 btn-lg mb-2" :href="route('me.exam-sessions.index')">
                    <x-feather name="send" class="align-text-middle me-2" />
                    Mulai Ujian Sekarang
                </x-button>
                @else
                <x-alert class="mb-1 mt-3 fw-bold">
                    Ujian masih belum di buka atau sudah berakhir.
                </x-alert>
                @endif
                <x-button as="a" color="danger" class="w-100" :href="route('me.exam-sessions.index')">
                    <x-feather name="trash" class="align-text-middle me-2" />
                    Bersihkan Pencarian
                </x-button>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-7 col-lg-8">
        <div class="card">
            <div class="card-body">
                <x-alert title="Informasi" color="info">
                    Silahkan login ujian terlebih dahulu untuk masuk ke detail ujian dan memulai ujian.
                </x-alert>
                <x-button type="button" color="dark" class="w-100 btn-lg" data-bs-toggle="modal"
                    data-bs-target="#login-exam-modal">
                    <x-feather name="key" class="align-text-middle me-2" />
                    Login Ujian
                </x-button>
            </div>
        </div>
    </div>
    @endif
</div>

<form action="{{ route('me.exam-sessions.index') }}" method="get" novalidate>
    <x-modal modalId="login-exam-modal" :simple="true">

        <x-slot:title>Login Ujian Dengan Nomor Sesi Ujian</x-slot:title>
        <input type="hidden" name="requested" value="true" />
        <div class="mb-2">
            <x-forms.label id="nik">Nomor Peserta</x-forms.label>
            <x-forms.input name="nik" :value="old('nik')" />
        </div>

        <div class="mb-2">
            <x-forms.label id="code">Nomor Sesi Ujian</x-forms.label>
            <x-forms.input name="code" :value="old('code')" />
        </div>

        <x-slot:footer>
            <button type="submit" class="btn btn-lg btn-dark w-100 mx-0" data-bs-dismiss="modal">
                <x-feather name="send" class="me-2" />
                Cek Sesi Ujian
            </button>
        </x-slot:footer>

    </x-modal>
</form>
@endsection