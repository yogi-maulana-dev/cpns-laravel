@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')

<div class="pre-loader d-flex align-items-center justify-content-center">
    <div>
        <img src="{{ asset('images/spinner.gif') }}" alt="Loading...." width="400">
    </div>
</div>
<div class="container">
    @yield('content')
</div>
@endsection

@push('script')
<script src="{{ asset('js/preloader.js') }}"></script>
@endpush