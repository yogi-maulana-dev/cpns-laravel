<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('storage/' . $appSetting->logo_icon) }}" type="image/x-icon" />

    @vite('resources/css/app.css')

    @include('partials.styles')

    @vite('resources/js/style.js')

    @stack('style')

    {{-- Livewire CSS --}}
    @livewireStyles

    <title>{{ $title }} | {{ $appSetting->web_name }}</title>
</head>

{{-- x-data for child element can use alpine functionalities --}}

<body class="custom-scrollbar @yield('body-classes', '')">

    {{-- realtime/ajax --}}
    <x-toast-container />

    <div class="pre-loader d-flex align-items-center justify-content-center">
        <div>
          <img src="{{ asset('images/kocang.gif') }}" alt="Loading...." width="400">
        </div>
    </div>

    @include('partials.toast-alerts')

    @yield('base')

    {{-- Livewire JS --}}
    @livewireScripts

    @vite(['resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
        integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
    </script>

    <script>
        const csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;
    </script>

    @stack('script')

</body>

</html>
