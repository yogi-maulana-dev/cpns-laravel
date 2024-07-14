<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta content="Sistem Project Yai" name="description">
    <meta content="ppdb,smk,mandiri" name="keywords">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('storage/'. $appSetting->logo_icon) }}" type="image/x-icon" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('packages/template/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/template/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/template/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/template/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/template/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('packages/template/css/style.css') }}" rel="stylesheet">

    @vite('resources/js/landing-style.js')

    @stack('style')

    <title>{{ $title }} | {{ $appSetting->web_name }}</title>

    <!-- =======================================================
  * Template Name: BizLand - v3.10.0
  * Template URL: https://bootstrapmade.com/bizland-bootstrap-business-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    @include('partials.toast-alerts')

    @if(\App\Services\PPDBService::isOpen())
    <!-- ======= Top Bar ======= -->
    <section id="topbar" class="d-flex align-items-center py-3">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <p class="m-0">PPDB Tahun Pelajaran {{ \App\Services\PPDBService::getFullActiveTahunPelajaran() }} Sudah
                Dibuka, Silahkan Daftarkan Diri anda melalui Formulir Online yang sudah kami sediakan.</p>
        </div>
    </section>
    @else
    <!-- ======= Top Bar ======= -->
    <section id="topbar" class="d-flex align-items-center py-3 text-bg-danger">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <p class="m-0">PPDB Masih Belum Dibuka!</p>
        </div>
    </section>
    @endif

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">

            <h1 class="logo">
                {{-- <a href="index.php" class="">PENGUMUMAN KELULUSAN</a> --}}
                <a href="{{ route('home.index') }}" class="logo"><img src="{{ asset('storage/'. $appSetting->logo) }}"
                        alt=""></a>
            </h1>
            <!-- Uncomment below if you prefer to use an image logo -->

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link{{ request()->routeIs('home.index') ? ' active' : '' }}"
                            href="{{ route('home.index') }}">Beranda</a></li>
                    <li><a class="nav-link{{ request()->routeIs('home.form') ? ' active' : '' }}"
                            href="{{ route('home.form') }}">Formulir Pendaftaran</a></li>
                    <li><a class="nav-link" href="{{ route('auth.login') }}">Login</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    @yield('content')

    <!-- Vendor JS Files -->
    <script src="{{ asset('packages/template/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('packages/template/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('packages/template/js/main.js') }}"></script>

    @vite('resources/js/landing.js')

    @stack('script')

</body>

</html>