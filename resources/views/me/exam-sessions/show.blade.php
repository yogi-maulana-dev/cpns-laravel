@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-7 order-2 order-lg-1">
        <div class="card">
            <div class="card-body">
                <h2 class="fw-semibold">Detail Sesi Ujian</h2>
                <hr class="mb-4">

                <div class="mb-3">
                    <small class="d-block fw-bold text-muted">Nomor Sesi</small>
                    <span class="mb-2 d-block">{{ $examSession->code }}</span>
                    <small class="d-block fw-bold text-muted">Nama</small>
                    <span class="mb-2 d-block">{{ $examSession->name }}</span>
                    <small class="d-block fw-bold text-muted">Keterangan</small>
                    <span class="mb-2 d-block">{!! nl2br($examSession->description) !!}</span>
                    <small class="d-block fw-bold text-muted">Waktu</small>
                    <span class="mb-2 d-block">{{ $examSession->time }} Menit</span>
                    <small class="d-block fw-bold text-muted">Tanggal & Waktu Mulai <x-tooltip
                            title="Waktu dimana peserta dapat memulai ujian.">
                            <x-feather name="info" />
                        </x-tooltip></small>
                    <span class="mb-2 d-block">{{ $examSession->start_at->translatedFormat('d F
                        Y H:i')
                        }}</span>
                    <small class="d-block fw-bold text-muted">Tanggal & Waktu Berakhir
                        <x-tooltip title="Waktu dimana peserta tidak dapat lagi memulai ujian.">
                            <x-feather name="info" />
                        </x-tooltip>
                    </small>
                    <span class="mb-2 d-block">{{ $examSession->end_at->translatedFormat('d F
                        Y H:i')
                        }}</span>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="row" class="text-center">No</th>
                            <th>Tipe Kelompok Soal</th>
                            <th>Jumlah Soal</th>
                            <th>Passing Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($examSession->examSessionSettings as $setting)
                        <tr>
                            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
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


                @if($participantExamResult && $participantExamResult->finished_at)
                <x-alert class="mb-1 mt-3" color="success" title="Informasi">
                    Ujian sudah anda selesaikan.
                </x-alert>
                <x-button as="a" :href="route('me.exam-sessions.result', $examSession)" color="dark"
                    class="w-100 btn-lg mb-2">
                    <x-feather name="file-text" class="align-text-middle me-2" />
                    Hasil Ujian Anda
                </x-button>
                @endif

                @if($participantExamResult && $participantExamResult->started_at &&
                !$participantExamResult->finished_at)
                <x-button as="a" :href="route('me.exam-sessions.exam', $examSession)" color="dark"
                    class="w-100 btn-lg mb-2">
                    <x-feather name="arrow-right" class="align-text-middle me-2" />
                    Lanjutkan Ujian
                </x-button>
                @endif

                @if(!$participantExamResult || (!$participantExamResult->started_at &&
                !$participantExamResult->finished_at))

                @if($examSession->isOpen())
                <x-button type="button" data-bs-toggle="modal" data-bs-target="#confirmExamModal" color="dark"
                    class="w-100 btn-lg mb-2">
                    <x-feather name="send" class="align-text-middle me-2" />
                    Mulai Ujian Sekarang
                </x-button>
                @endif

                @if($examSession->isNotStartedYet())
                <x-alert class="mb-1 mt-3" color="warning" title="Informasi">
                    Ujian masih belum di buka.
                </x-alert>
                @endif

                @if($examSession->isClosed())
                <x-alert class="mb-1 mt-3" title="Informasi">
                    Ujian sudah berakhir dan ditutup.
                </x-alert>
                @endif

                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-3 order-1 order-lg-2">
        <div class="card">
            <div class="card-body">
                <h2 class="fw-semibold">Biodata Peserta Ujian</h2>
                <hr class="mb-4">

                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('storage/' . $participant->picture) }}" alt="Foto Peserta"
                            class="img-thumbnail d-block w-100 rounded-circle mb-4 d-block mx-auto"
                            style="max-width: 150px;" />
                    </div>
                    <div class="col-md-9">
                        <small class="d-block fw-bold text-muted">NIK</small>
                        <span class="mb-2 d-block">{{ $participant->nik }}</span>
                        <small class="d-block fw-bold text-muted">Nama Lengkap</small>
                        <span class="mb-2 d-block">{{ $participant->name }}</span>
                        <small class="d-block fw-bold text-muted">Tanggal Lahir</small>
                        <span class="mb-2 d-block">{{ $participant->date_of_birth->translatedFormat('d F
                            Y')
                            }}</span>
                        <small class="d-block fw-bold text-muted">Jenis Kelamin</small>
                        <span class="mb-2 d-block">{{ $participant->getGenderText() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-alert fade py-5" id="confirmExamModal" tabindex="-1" data-bs-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('me.exam-sessions.exam', $examSession) }}" method="post">
                @csrf

                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Konfirmasi</h5>
                    <p class="mb-0">Siapkah diri anda untuk mengikuti ujian ini?</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <button type="submit"
                        class="btn btn-lg btn-link link-dark fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"><strong>Mulai
                            Ujian Sekarang</strong></button>
                    <button type="button"
                        class="btn btn-lg btn-link link-danger fs-6 text-decoration-none col-6 m-0 rounded-0"
                        data-bs-dismiss="modal">Tidak Jadi</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection