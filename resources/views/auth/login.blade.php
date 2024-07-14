@extends('layouts.auth')

@section('content')
<main class="form-signin card w-100 m-auto">
    <div class="card-body">
        <form method="POST" action="{{ route('auth.login', ['to' => request('to')]) }}" id="login-form">
            <h1 class="h3 mb-3 fw-bold text-center">{{ $appSetting->web_name }}</h1>
            <p class="text-center text-muted">Silahkan masuk dengan email dan password yang benar</p>
            <input type="hidden" name="redirect" value="{{ request('redirect') }}" />
            <div class="form-floating">
                <input type="email" class="form-control input-floating-first" id="floatingInputEmail" name="email"
                    placeholder="name@example.com" autofocus />
                <label for="floatingInputEmail">Email address</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control input-floating-last" id="floatingPassword" name="password"
                    placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckRemember">
                <label class="form-check-label" for="flexCheckRemember">
                    Ingatkan Saya di Perangkat ini
                </label>
            </div>

            <button class="w-100 btn btn-success fw-bold py-3" type="submit" id="login-form-button">Masuk</button>

            <div class="mt-3">
                <p class="m-0">Belum mempunyai akun peserta?, <a href="{{ route('auth.registration') }}">daftar
                        disini
                        sekarang!</a></p>
            </div>
        </form>
    </div>
</main>
@endsection

@push('script')
@vite(['resources/js/pages/login.js'])
@endpush