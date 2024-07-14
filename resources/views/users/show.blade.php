@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-6 col-md-8">
        <div class="card">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">ID</span>
                        <p>{{ $user->id }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nama
                            Lengkap</span>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Email</span>
                        <p>
                            <a href="mailto:{{ $user->email }}" class="link-primary text-decoration-underline">{{
                                $user->email }}</a>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Role / Hak
                            Akses</span>
                        <p>
                            @php
                            $badge = match ($user->role) {
                            \App\Enums\UserRole::SUPERADMIN() => '<span
                                class="badge text-primary-emphasis bg-primary-subtle border border-primary-subtle me-1 mb-1">Superadmin</span>',
                            \App\Enums\UserRole::OPERATOR_UJIAN() => '<span
                                class="badge text-success-emphasis bg-success-subtle border border-success-subtle me-1 mb-1">Operator
                                Ujian</span>',
                            \App\Enums\UserRole::OPERATOR_SOAL() => '<span
                                class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle me-1 mb-1">Operator
                                Soal</span>',
                            \App\Enums\UserRole::PARTICIPANT() => '<span
                                class="badge text-info-emphasis bg-info-subtle border border-info-subtle me-1 mb-1">Peserta</span>',
                            default => '-'
                            };
                            @endphp
                            {!! $badge !!}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                        <p>
                            <span class="d-block">{{ $user->created_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $user->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            Pada</span>
                        <p>
                            <span class="d-block">{{ $user->updated_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $user->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-action-buttons :index="route('users.index')" for="show" :edit="route('users.edit', $user)" />
    </div>
</div>
@endsection