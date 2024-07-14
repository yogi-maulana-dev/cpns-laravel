@extends('layouts.app')

@section('content')
<div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="fs-3 mb-2 fw-light">Apa Kabar, <span class="fw-semibold">{{
                                auth()->user()->name
                                }}?</span></h1>
                        <h4 class="fs-5 text-primary mb-4 fw-normal">{{ now()->translatedFormat('F d, Y H:i') }}</h4>

                        <div>
                            <a href="{{ route('profiles.index') }}"
                                class="btn btn-outline-primary fw-bold d-flex justify-content-center align-items-center"
                                style="width: max-content;">Profile
                                Saya <span data-feather="arrow-right" class="ms-1"></span></a>
                        </div>
                    </div>

                    <div>
                        @if ($appSetting->logo)
                        <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="Logo" width="100" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            @foreach ($cardStats as $cardStat)
            <span class="fw-bold text-uppercase p-2 mb-2 rounded d-block border bg-white text-primary text-center">{{
                $cardStat['name']
                }}</span>
            <div class="row">
                <div class="col-12">
                    @foreach (array_chunk($cardStat['items'], array_key_exists('cols', $cardStat) ? $cardStat['cols'] :
                    3) as $cardStatItems)
                    <div class="card-group mb-2">
                        @foreach ($cardStatItems as $stat)
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h3 class="fs-5 fw-semibold">{{ $stat['label'] }}</h3>
                                    <div>
                                        <div class="bg-light border text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg"
                                            style="width: 40px; height: 40px;">
                                            <span data-feather="{{ $stat['icon'] }}" stroke-width="2.5"></span>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="fs-1 fw-extrabold mb-2">{{ $stat['count'] }} <sup
                                        class="fs-6 text-muted fw-normal">{{
                                        $stat['prefix']
                                        }}</sup></h2>
                                @if ($stat['more_info_link'])
                                <a href="{{ $stat['more_info_link'] }}" class="text-decoration-underline d-block">Lebih
                                    Lengkap <span data-feather="arrow-right"></span></a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection