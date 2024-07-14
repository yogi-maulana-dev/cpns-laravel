@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-8">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body py-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">ID</span>
                            <p>{{ $participant->id }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nomor Peserta</span>
                            <p>{{ $participant->nik }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Alamat
                                Email</span>
                            <p>{{ $participant->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nama
                                Lengkap</span>
                            <p>{{ $participant->name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Alamat</span>
                            <p>{!! nl2br($participant->address) !!}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Jenis
                                Kelamin</span>
                            <p>{{ $participant->getGenderText() }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tempat
                                Lahir</span>
                            <p>{{ $participant->place_of_birth }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tanggal
                                Lahir</span>
                            <p>{{ $participant->date_of_birth->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Status
                                Aktif</span>
                            <p>
                                @if ($participant->user_id)
                                    <x-badge as="a" color="success" :href="route('users.show', $participant->user_id)">Aktif
                                    </x-badge>
                                @else
                                    <x-badge as="span" color="danger">Tidak Aktif</x-badge>
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                            <p>
                                <span
                                    class="d-block">{{ $participant->created_at->translatedFormat('d F Y
                                                                    H:i') }}</span>
                                <span>({{ $participant->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                                Pada</span>
                            <p>
                                <span
                                    class="d-block">{{ $participant->updated_at->translatedFormat('d F Y
                                                                    H:i') }}</span>
                                <span>({{ $participant->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <x-action-buttons :index="route('participants.index')" for="show" :edit="route('participants.edit', $participant)" />
        </div>
    </div>
@endsection
