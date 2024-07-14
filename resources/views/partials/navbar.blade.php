<header class="navbar navbar-warning sticky-top bg-warning shadow p-3 w-100 px-0 px-lg-3">
    <div class="container-lg d-flex justify-content-between">

        @if(!$noSidebar)
        <button class="btn btn-warning py-2 d-flex align-items-center justify-content-center d-lg-none"
            id="sidebar-trigger">
            <x-feather name="menu" />
        </button>
        <div class="d-none d-lg-block"></div>
        @endif

        <a class="fs-6 fw-bold text-white text-decoration-none d-block" href="">{{
            $appSetting->web_name }}</a>
        <div class="dropdown">
            <button class="btn btn-warning text-light dropdown-toggle-no-icon dropdown-toggle" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <x-feather name="user" />
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
                <li><a class="dropdown-item rounded-2" href="{{ route('profiles.index') }}">Profile Saya</a></li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item rounded-2 w-100 text-bg-warning" href="#" data-bs-toggle="modal"
                        data-bs-target="#logOutModal">
                        <span data-feather="log-out" class="align-text-middle me-2"></span>
                        Keluar</a>
                </li>
            </ul>
        </div>
        {{-- <input class="form-control form-control-warning w-100 rounded-0 border-0" type="text" placeholder="Search"
            aria-label="Search">
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <form action="{{ route('auth.logout') }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button class="bg-transparent border-0 nav-link text-warning px-3" href="#">Keluar</button>
                </form>
            </div>
        </div> --}}
    </div>
</header>