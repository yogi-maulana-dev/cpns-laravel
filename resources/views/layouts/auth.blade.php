@extends('layouts.base')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@if ($appSetting->login_background)
    @php
        $loginBg = asset('storage/' . $appSetting->login_background);
    @endphp
    @push('style')
        <style>
            body {
                background-image: url('{{ $loginBg }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
    @endpush
@else
    <style>
        body {
            background-image: url('{{ asset('images/stacked-waves-haikei.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
@endif

@section('body-classes', 'bg-blue')

@section('base')
    <div class="w-100">
        @if ($appSetting->logo)
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="" width="100" class="d-block mb-3">
            </div>
        @endif

        @yield('content')

        <div class="text-center">
            <p class="mt-5 mb-3 text-dark fw-bold text-center bg-white border rounded py-2 px-3 d-inline-block">
                &copy; {{ date('Y') }}</p>
        </div>


    </div>
@endsection
