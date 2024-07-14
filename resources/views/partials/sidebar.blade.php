<nav id="sidebarMenu" class="sidebar d-block d-lg-flex flex-shrink-0 flex-column border-end">
    <div class="position-sticky pt-4">
        <div class="position-absolute d-lg-none" style="top: 10px; right: 10px;">
            <button class="btn btn-light border btn-sm d-flex align-items-center justify-content-center py-2"
                id="sidebar-trigger-close">
                <x-feather name="x" />
            </button>
        </div>

        <div class="d-flex justify-content-center w-100 mb-4 flex-column align-items-center border-bottom pb-3">


            @if($appSetting->logo)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="" width="60" class="d-block sidebar-logo">
            </div>
            @endif

            <h6 class="fw-bold text-dark text-center">{{ $appSetting->web_name }}</h6>
        </div>

        @if (auth()->user()->isSuperadmin())
        <ul class="list-unstyled px-2">
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <span>Menu Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('dashboard.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('dashboard.index') }}">
                                <x-feather name="home" class="sidebar-icon" />
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#basic-collapse" aria-expanded="false">
                    <span>Data Dasar</span>
                </button>
                <div class="collapse collapse-group mb-2" id="basic-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('question-group-types.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('question-group-types.index') }}">
                                <x-feather name="box" class="sidebar-icon" />
                                Data Tipe Kelompok Soal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('question-types.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('question-types.index') }}">
                                <x-feather name="tag" class="sidebar-icon" />
                                Data Tipe Soal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('questions.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('questions.index') }}">
                                <x-feather name="help-circle" class="sidebar-icon" />
                                Data Soal
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#primary-collapse" aria-expanded="false">
                    <span>Data Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="primary-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('participants.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('participants.index') }}">
                                <x-feather name="users" class="sidebar-icon" />
                                Data Peserta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('exam-sessions.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('exam-sessions.index') }}">
                                <x-feather name="file-text" class="sidebar-icon" />
                                Data Sesi Ujian
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#log-collapse" aria-expanded="false">
                    <span>Data Log</span>
                </button>
                <div class="collapse collapse-group mb-2" id="log-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('log-logins.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('log-logins.index') }}">
                                <x-feather name="activity" class="sidebar-icon" />
                                Log Aktivitas Login
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="false">
                    <span>Users Management</span>
                </button>
                <div class="collapse collapse-group mb-2" id="users-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('users.*') && !request()->routeIs('users.trash') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('users.index') }}">
                                <x-feather name="users" class="sidebar-icon" />
                                Data Akun Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('users.trash') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('users.trash') }}">
                                <x-feather name="trash" class="sidebar-icon" />
                                Data Sampah Users
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#setting-collapse" aria-expanded="false">
                    <span>Pengaturan</span>
                </button>
                <div class="collapse collapse-group mb-2" id="setting-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('app-settings.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('app-settings.index') }}">
                                <x-feather name="settings" class="sidebar-icon" />
                                Pengaturan Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('profiles.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('profiles.index') }}">
                                <x-feather name="user" class="sidebar-icon" />
                                Profile Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        @endif

        @if (auth()->user()->isOperatorUjian())
        <ul class="list-unstyled px-2">
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <span>Menu Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('dashboard.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('dashboard.index') }}">
                                <x-feather name="home" class="sidebar-icon" />
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#primary-collapse" aria-expanded="false">
                    <span>Data Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="primary-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('participants.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('participants.index') }}">
                                <x-feather name="users" class="sidebar-icon" />
                                Data Peserta
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#setting-collapse" aria-expanded="false">
                    <span>Pengaturan</span>
                </button>
                <div class="collapse collapse-group mb-2" id="setting-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('profiles.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('profiles.index') }}">
                                <x-feather name="user" class="sidebar-icon" />
                                Profile Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        @endif

        @if (auth()->user()->isOperatorSoal())
        <ul class="list-unstyled px-2">
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <span>Menu Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('dashboard.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('dashboard.index') }}">
                                <x-feather name="home" class="sidebar-icon" />
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#basic-collapse" aria-expanded="false">
                    <span>Data Dasar</span>
                </button>
                <div class="collapse collapse-group mb-2" id="basic-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('question-group-types.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('question-group-types.index') }}">
                                <x-feather name="box" class="sidebar-icon" />
                                Data Tipe Kelompok Soal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('question-types.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('question-types.index') }}">
                                <x-feather name="tag" class="sidebar-icon" />
                                Data Tipe Soal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('questions.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('questions.index') }}">
                                <x-feather name="help-circle" class="sidebar-icon" />
                                Data Soal
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#setting-collapse" aria-expanded="false">
                    <span>Pengaturan</span>
                </button>
                <div class="collapse collapse-group mb-2" id="setting-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('profiles.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('profiles.index') }}">
                                <x-feather name="user" class="sidebar-icon" />
                                Profile Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        @endif

        @if (auth()->user()->isParticipant())
        <ul class="list-unstyled px-2">
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <span>Menu Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('dashboard.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('dashboard.index') }}">
                                <x-feather name="home" class="sidebar-icon" />
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('me.exam-sessions.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('me.exam-sessions.index') }}">
                                <x-feather name="file-text" class="sidebar-icon" />
                                Sesi Ujian
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#primary-collapse" aria-expanded="false">
                    <span>Data Utama</span>
                </button>
                <div class="collapse collapse-group mb-2" id="primary-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ (request()->routeIs('me.exam-sessions.*') && !request()->routeIs('me.exam-sessions.histories')) ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('me.exam-sessions.list') }}">
                                <x-feather name="file-text" class="sidebar-icon" />
                                Sesi Ujian Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('me.exam-sessions.histories') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('me.exam-sessions.histories') }}">
                                <x-feather name="check-square" class="sidebar-icon" />
                                Sejarah Ujian Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button
                    class="sidebar-heading mb-1 text-muted btn-toggle d-inline-flex align-items-center collapsed w-100"
                    data-bs-toggle="collapse" data-bs-target="#setting-collapse" aria-expanded="false">
                    <span>Pengaturan</span>
                </button>
                <div class="collapse collapse-group mb-2" id="setting-collapse">
                    <ul class="btn-toggle-nav list-unstyled pb-1" style="line-height: 26px;">
                        <li class="nav-item">
                            <a class="nav-link sidebar-nav-link {{ request()->routeIs('profiles.*') ? 'active fw-bold' : '' }}"
                                aria-current="page" href="{{ route('profiles.index') }}">
                                <x-feather name="user" class="sidebar-icon" />
                                Profile Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        @endif

        <hr>

        <div class="px-3 pb-4">
            <button type="button" class="fw-bold btn btn-warning w-100" data-bs-toggle="modal"
                data-bs-target="#logOutModal">
                <x-feather name="log-out" />
                Keluar
            </button>
        </div>
    </div>
</nav>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal modal-alert fade py-5" id="logOutModal" tabindex="-1" data-bs-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('auth.logout') }}" method="post">
                @method('DELETE')
                @csrf
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Konfirmasi</h5>
                    <p class="mb-0">Apakah anda yakin ingin keluar?</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <button type="submit"
                        class="btn btn-lg btn-link link-warning fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"><strong>Keluar
                            Sekarang</strong></button>
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"
                        data-bs-dismiss="modal">Tidak Jadi</button>
                </div>
            </form>

        </div>
    </div>
</div>