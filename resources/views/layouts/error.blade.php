@extends('layouts.base')

@push('style')
<style>
    body {
        position: relative;
        min-height: 100vh;
    }

    .image {
        width: 400px;
        height: 200px;
        background-image: url('{{ asset("images/bg-patternpad-2.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1;
    }
</style>
@endpush


@section('base')
<div class="image position-absolute bottom-0 start-0"></div>
<div class="container-fluid">
    <div class="row">
        <main class="mx-auto col-lg-10 px-md-4">
            <div class="container">
                <div class="row">
                    <div
                        class="col d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-2 mb-3 border-bottom">
                        <h1 class="h2">
                            <span class="text-muted h2 fw-bold">#</span>
                            {{ $title }}
                        </h1>
                        {{-- <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                <span data-feather="calendar" class="align-text-middle"></span>
                                This week
                            </button>
                        </div> --}}
                        @yield('buttons')
                    </div>
                </div>
            </div>

            <div class="py-4">
                <div class="container-lg">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</div>
@endsection