@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-12 col-lg-8">
        <div class="card p-4 mb-4">
            <h2 class="fw-semibold">Profile Saya</h2>
            <hr class="mb-4">

            <dl class="row">
                <dt class="col-sm-4">
                    Nama Lengkap
                </dt>
                <dd class="col-sm-8">
                    {{ auth()->user()->name }}
                </dd>
                <dt class="col-sm-4">
                    Email
                </dt>
                <dd class="col-sm-8">
                    <a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a>
                </dd>
                <dt class="col-sm-4">
                    Hak Level
                </dt>
                <dd class="col-sm-8">
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
                </dd>
                <dt class="col-sm-4">
                    Bergabung Pada
                </dt>
                <dd class="col-sm-8">
                    {{ auth()->user()->created_at->translatedFormat('F d, Y H:i') }}
                </dd>
                <dt class="col-sm-4">
                    Terakhir Kali Diubah Pada
                </dt>
                <dd class="col-sm-8">
                    {{ auth()->user()->updated_at->translatedFormat('F d, Y H:i') }}
                </dd>
            </dl>
        </div>

        @if ($participant)
        <div class="card p-4 mb-4">
            <h2 class="fw-semibold">Biodata Saya</h2>
            <hr class="mb-4">

            <dl class="row">
                <dt class="col-sm-4 mb-2">
                    Foto
                </dt>
                <dd class="col-sm-8">
                    <img src="{{ asset('storage/' . $participant->picture) }}" alt="Foto Peserta" class="img-thumbnail"
                        width="100" />
                </dd>
                <dt class="col-sm-4 mb-2">
                    Nomor Peserta
                </dt>
                <dd class="col-sm-8">
                    {{ $participant->nik }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Nama Lengkap
                </dt>
                <dd class="col-sm-8">
                    {{ $participant->name }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Alamat
                </dt>
                <dd class="col-sm-8">
                    {!! nl2br($participant->address) !!}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Tanggal Lahir
                </dt>
                <dd class="col-sm-8">
                    {{ $participant->date_of_birth->translatedFormat('d F Y') }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Tempat Lahir
                </dt>
                <dd class="col-sm-8">
                    {!! nl2br($participant->place_of_birth) !!}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Jenis Kelamin
                </dt>
                <dd class="col-sm-8">
                    {{ $participant->getGenderText() }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Nomor Telepon
                </dt>
                <dd class="col-sm-8">
                    {{ $participant->phone_number }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Bergabung Pada
                </dt>
                <dd class="col-sm-8">
                    {{ auth()->user()->created_at->translatedFormat('F d, Y H:i') }}
                </dd>
                <dt class="col-sm-4 mb-2">
                    Terakhir Kali Diubah Pada
                </dt>
                <dd class="col-sm-8">
                    {{ auth()->user()->updated_at->translatedFormat('F d, Y H:i') }}
                </dd>
            </dl>
        </div>
        @endif

        <div class="card p-4">
            <h2 class="fw-semibold">Pengaturan Profile Anda</h2>
            <hr class="mb-4">

            <form action="{{ route('profiles.update') }}" method="POST">
                @csrf
                @method('PUT')

                <dl class="row">
                    <dt class="col-sm-4">
                        <x-forms.label id="name">Nama User Anda</x-forms.label>
                    </dt>
                    <dd class="col-sm-8">
                        <x-forms.input name="name" :value="old('name', auth()->user()->name)" />
                    </dd>
                    <dt class="col-sm-4">
                        <x-forms.label id="email">Email</x-forms.label>
                    </dt>
                    <dd class="col-sm-8">
                        <x-forms.input name="email" :value="old('email', auth()->user()->email)" disabled />
                        <small class="text-muted d-block mt-2">Anda tidak diperbolehkan mengubah email anda karena
                            alasan keamanan, Untuk mengubah email silahkan hubungi administrator
                            website ini.</small>
                    </dd>
                </dl>
                <div class="d-flex justify-content-end">
                    <x-button color="primary" class="fw-bold" type="submit">
                        <x-feather name="save" class="me-1" />
                        Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-8">
        <div class="card p-4">
            <h2 class="fw-semibold">Ubah Password Akun Anda</h2>
            <hr class="mb-4">

            <form action="{{ route('profiles.update-password') }}" method="POST"
                onsubmit="return confirm('Apakah anda yakin ingin mengubah password akun anda dan keluar?')">
                @csrf
                @method('PUT')

                <dl class="row">
                    <dt class="col-sm-4">
                        <x-forms.label id="password">Password Lama</x-forms.label>
                    </dt>
                    <dd class="col-sm-8">
                        <x-forms.input name="password" type="password" />
                    </dd>
                    <div class="col-12">
                        <hr class="d-block px-5 my-4">
                    </div>
                    <dt class="col-sm-4">
                        <x-forms.label id="new_password">Password Baru</x-forms.label>
                    </dt>
                    <dd class="col-sm-8">
                        <x-forms.input name="new_password" type="password" />
                    </dd>
                    <dt class="col-sm-4">
                        <x-forms.label id="new_password_confirmation">Ulangi Password Baru</x-forms.label>
                    </dt>
                    <dd class="col-sm-8">
                        <x-forms.input name="new_password_confirmation" type="password" />
                    </dd>
                </dl>
                <div class="d-flex justify-content-end">
                    <x-button color="warning" class="fw-bold" type="submit">
                        <span data-feather="save"></span>
                        Update & Logout
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection