{{-- @extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden')) --}}

@extends('layouts.error')

@php
$title = __($exception->getMessage() ?: 'Forbidden')
@endphp
@section('content')
<div class="py-5 my-5 text-center">
    <h1 class="fw-bold text-danger">403</h1>
    <h5>{{ $title }}</h5>
    <hr class="mx-auto d-block my-4" style="width: 100px;">
    <p class="text-danger fw-bold text-decoration-underline mb-4 d-block">Anda Tidak Memiliki Hak Untuk Mengakses
        Halaman Ini</p>

    <div>
        <a class="btn btn-sm btn-dark fw-bold" href="{{ route('dashboard.index') }}">Kembali Ke Halaman Dashboard</a>
        <a class="btn btn-sm btn-outline-success fw-bold" href="{{ url()->previous() }}">Kembali Ke Halaman
            Sebelumnya</a>
    </div>
</div>

@endsection