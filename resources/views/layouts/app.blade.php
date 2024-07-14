@extends('layouts.base')

@section('body-classes', 'bg-blue')

@section('base')

<div class="sidebar-backdrop"></div>

<div class="vh-100">
    <div class="d-flex">

        @include('partials.sidebar')

        <main class="main d-flex flex-grow-1 flex-column">
            @include('partials.navbar', ['noSidebar' => false])

            <div class="ms-sm-auto px-md-4 w-100">
                <div class="container-lg">
                    <div class="row pt-4 pb-2">
                        <div class="col">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">

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

                            <hr class="mb-0">
                        </div>
                    </div>
                </div>

                <div class="py-4">
                    <div class="container-lg" style="min-height: 60vh;">
                        @yield('content')
                    </div>
                </div>

                @if ($appSetting->footer)
                <footer class="container-lg">
                    <div
                        class="bg-white rounded border p-3 text-center border-top d-flex align-items-center justify-content-between flex-column flex-md-row mb-3">
                        <div class=" mb-2 mb-md-0">
                            @if($appSetting->logo)
                            <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="" width="40">
                            @endif
                        </div>
                        <div>{{ $appSetting->footer }}</div>
                    </div>
                </footer>
                @endif

            </div>
        </main>

    </div>
</div>
@endsection

@push('script')
@vite('resources/js/modules/sidebar.js');
@endpush