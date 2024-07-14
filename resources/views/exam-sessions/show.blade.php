@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">ID</span>
                        <p>{{ $examSession->id }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nomor Sesi</span>
                        <p>{{ $examSession->code }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nama Sesi
                            Ujian</span>
                        <p>{{ $examSession->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Keterangan</span>
                        <p>{!! nl2br($examSession->description ?? '-') !!}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Waktu</span>
                        <p>{{ $examSession->time }} Menit</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tanggal & Waktu
                            Mulai</span>
                        <p>{{ $examSession->start_at->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tanggal & Waktu
                            Berakhir</span>
                        <p>{{ $examSession->end_at->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Urutan Soal</span>
                        <p>
                            <x-badge as="span">{{ \App\Enums\OrderOfQuestion::getList()[$examSession->order_of_question]
                                ?? '' }}</x-badge>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Status Hasil Akhir
                            Jawaban</span>
                        <p>
                            <x-badge as="span">{{
                                \App\Enums\ResultDisplayStatus::getList()[$examSession->result_display_status]
                                ?? '' }}</x-badge>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat oleh</span>
                        <p>
                            <x-badge as="a" :href="route('users.show', $examSession->created_by)">{{
                                $examSession->createdBy->name }}</x-badge>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            terakhir kali oleh</span>
                        <p>
                            @if ($examSession->last_updated_by)
                            <x-badge as="a" :href="route('users.show', $examSession->last_updated_by)">{{
                                $examSession->lastUpdatedBy->name }}</x-badge>
                            @else
                            -
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                        <p>
                            <span class="d-block">{{ $examSession->created_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $examSession->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            Pada</span>
                        <p>
                            <span class="d-block">{{ $examSession->updated_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $examSession->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-action-buttons :index="route('exam-sessions.index')" for="show"
            :edit="route('exam-sessions.edit', $examSession)" />
    </div>
</div>
@endsection