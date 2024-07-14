@extends('layouts.auth')

@section('content')
    <main class="form-registration card w-100 m-auto">
        <div class="card-body">
            <form method="POST" action="{{ route('auth.registration') }}">
                @csrf
                <h1 class="h3 mb-3 fw-bold text-center">{{ $appSetting->web_name }}</h1>
                <p class="text-center text-muted mb-2">Daftarkan Diri anda Sebagai Peserta Ujian</p>

                @if ($errors->any())
                    <x-alert feather="info" title="Gagal Melakukan Pendaftaran">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                <span class="fw-bold text-dark mb-2">1. Data Diri Anda</span>
                <hr />

                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <input type="number" class="form-control mb-2" id="nik" name="nik"
                                placeholder="name@example.com" min="0000000000000000" max="9999999999999999"
                                value="{{ old('nik') }}" />
                            <label for="nik">Nomor Peserta</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="email" class="form-control mb-2" id="email" name="email"
                                placeholder="name@example.com" value="{{ old('email') }}" />
                            <label for="email">Alamat Email</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control mb-2" id="name" name="name" placeholder="name"
                                value="{{ old('name') }}" />
                            <label for="name">Nama Lengkap</label>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control mb-2" id="address" name="address" placeholder="address" place_of_birth"
                                style="resize: none; height: 100px;">{{ old('address') }}</textarea>
                            <label for="address">Alamat</label>
                        </div>

                        <div class="form-floating">
                            <input class="form-control mb-2" type="date" id="date_of_birth" name="date_of_birth"
                                placeholder="date_of_birth" value="{{ old('date_of_birth') }}" />
                            <label for="date_of_birth">Tanggal Lahir</label>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="form-floating">
                            <textarea class="form-control mb-2" id="place_of_birth" name="place_of_birth" placeholder="place_of_birth"
                                style="resize: none; height: 100px;">{{ old('place_of_birth') }}</textarea>
                            <label for="place_of_birth">Tempat Lahir</label>
                        </div>

                        <div class="form-floating">
                            <select class="form-control mb-2" id="gender" name="gender" placeholder="gender">
                                <option value="0" @selected(!old('gender'))>Laki-Laki</option>
                                <option value="1" @selected(old('gender'))>Perempuan</option>
                            </select>
                            <label for="gender">Jenis Kelammin</label>
                        </div>

                        <div class="form-floating">
                            <input class="form-control mb-2" id="phone_number" name="phone_number"
                                placeholder="phone_number" value="{{ old('phone_number') }}" />
                            <label for="phone_number">Nomor Telepon</label>
                        </div>
                    </div>
                </div>

                <span class="fw-bold text-dark mb-2">2. Password</span>
                <hr />

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Password">
                            <label for="password_confirmation">Konfirmasi Password</label>
                        </div>
                    </div>
                </div>


                <button class="w-100 btn btn-dark fw-bold py-3" type="submit">
                    <x-feather name="send" class="me-2" />
                    Daftar Sekarang
                </button>

                <div class="mt-3 text-center">
                    <p class="m-0">Sudah terdaftar? Silakan masuk ke akunmu <a
                            href="{{ route('auth.login') }}">disini</a></p>
                </div>
            </form>
        </div>
    </main>
@endsection
