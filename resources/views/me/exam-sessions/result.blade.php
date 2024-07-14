@extends('layouts.exam')

@section('content')
<div class="row">
    <div class="col-lg-3 order-2 order-lg-1 mb-2">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $participant->picture) }}" alt="Foto Peserta" class="img-thumbnail d-block rounded-circle mx-auto mb-2" style="width: 120px;" />
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
    <div class="col-lg-6 order-1 order-lg-2 mb-2">
        <div class="card">
            <div class="card-body py-5">
                <h1 class="text-center fw-bold mb-4 d-block fs-3">Hasil Akhir Ujian Anda</h1>

                <div class="text-center mb-3">
                    <h3 class="fs-1 fw-bold text-bg-white border rounded px-4 py-2 d-inline-block mx-auto text-center">
                        {{ $participantExamResult->total_score }}
                    </h3>
                </div>

                <div class="mx-auto text-center" style="width: 100%; max-width: 400px;">
                    @if($participantExamResult->is_passed)
                    <x-alert color="success">
                        <h2 class="fs-5 m-0">Selamat, Anda Memenuhi Nilai Ambang Batas (Passing Grade)
                            Yang
                            Sudah Ditentukan.
                        </h2>
                    </x-alert>
                    @else
                    <x-alert color="danger">
                        <h2 class="fs-5 m-0">Maaf, Anda Belum Memenuhi Nilai Ambang Batas (Passing
                            Grade)
                            Yang
                            Sudah Ditentukan.
                        </h2>
                    </x-alert>
                    @endif
                </div>

                <div class="mx-auto" style="width: 100%; max-width: 400px;">
                    @foreach ($examSession->examSessionSettings as $setting)
                    @php
                    $examScore = $participantExamResult->examScoreQGTs
                    ->where('question_group_type_id',
                    $setting->questionGroupType->id)->first();
                    @endphp
                    <div class="alert alert-{{ $examScore->is_passed ? 'success' : 'danger' }}">
                        <h3 class="fs-2 m-0 fw-bold d-flex align-items-center justify-content-between">
                            <span>{{ $setting->questionGroupType->name }}</span>
                            <span>
                                <span>{{ $examScore->total_score }}</span>
                                <span class="fw-bold">/</span>
                                <span class="text-secondary">{{ $setting->passing_grade }}</span>
                            </span>
                        </h3>
                    </div>
                    <div>
                        <p class="m-0 fw-light d-flex align-items-center justify-content-between text-sm mb-2 text-uppercase">
                            <span>Jawaban Benar</span>
                            <span>
                                <span>{{ $examScore->correct_answer_count }}</span>
                            </span>
                        </p>
                        <p class="m-0 fw-light d-flex align-items-center justify-content-between text-sm mb-2 text-uppercase">
                            <span>Jawaban Salah</span>
                            <span>
                                <span>{{ $examScore->wrong_answer_count }}</span>
                            </span>
                        </p>
                        <p class="m-0 fw-light d-flex align-items-center justify-content-between text-sm mb-2 text-uppercase">
                            <span>Tidak di jawab</span>
                            <span>
                                <span>{{ $examScore->unanswered_count }}</span>
                            </span>
                        </p>
                    </div>
                    <hr />
                    @endforeach
                </div>

                @if($examSession->result_display_status != \App\Enums\ResultDisplayStatus::NOTHING())
                <form action="{{ route('me.exam-sessions.result', $examSession) }}" method="post" class="mb-2 text-center">
                    @csrf

                    <x-button color="dark" type="submit">
                        <x-feather name="file-text" class="me-2" />
                        Download Hasil Ujian
                    </x-button>
                </form>
                @endif

                <div class="text-center py-4">
                    <x-button color="dark" class="btn-lg" as="a" :href="route('dashboard.index')">
                        <x-feather name="arrow-left" class="me-2" />
                        Kembali Ke Dashboard
                    </x-button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 order-3 order-lg-3 mb-2">
        <div class="card mb-2">
            <div class="card-body text-center">
                <small class="d-block fw-bold text-muted">Nomor Sesi</small>
                <span class="mb-2 d-block">{{ $examSession->code }}</span>
                <small class="d-block fw-bold text-muted">Nama</small>
                <span class="mb-2 d-block">{{ $examSession->name }}</span>
                <small class="d-block fw-bold text-muted">Waktu</small>
                <span class="mb-2 d-block">{{ $examSession->time }} Menit</span>
                <small class="d-block fw-bold text-muted">Tanggal & Waktu Mulai</small>
                <span class="mb-2 d-block">{{ $examSession->start_at->translatedFormat('d F
                    Y H:i')
                    }}</span>
                <small class="d-block fw-bold text-muted">Tanggal & Waktu Berakhir</small>
                <span class="mb-2 d-block">{{ $examSession->end_at->translatedFormat('d F
                    Y H:i')
                    }}</span>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body text-center">
                <small class="d-block fw-bold text-muted">Jumlah Jawaban Benar</small>
                <span class="mb-2 d-block">{{ $participantExamResult->correct_answer_count }}</span>
                <small class="d-block fw-bold text-muted">Jumlah Jawaban Salah</small>
                <span class="mb-2 d-block">{{ $participantExamResult->wrong_answer_count }}</span>
                <small class="d-block fw-bold text-muted">Jumlah Tidak di Jawab</small>
                <span class="mb-2 d-block">{{ $participantExamResult->unanswered_count }}</span>
                <small class="d-block fw-bold text-muted">Waktu Mulai</small>
                <span class="mb-2 d-block">{{ $participantExamResult->started_at->translatedFormat('d F
                    Y H:i')
                    }}</span>
                <small class="d-block fw-bold text-muted">Waktu Selesai</small>
                <span class="mb-2 d-block">{{ $participantExamResult->finished_at->translatedFormat('d F
                    Y H:i')
                    }}</span>
            </div>
        </div>
    </div>
</div>
@endsection