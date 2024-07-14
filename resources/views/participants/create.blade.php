@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col">
            <form action="{{ route('participants.store') }}" method="post" novalidate enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        Form {{ $title }}
                    </div>

                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <x-forms.label id="picture" :required="false">Foto</x-forms.label>
                                    <div class="row align-items-center">
                                        <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                            <div class="bg-light border rounded overflow-hidden d-grid"
                                                style="min-height: 50px; height: 100%; place-content: center;">
                                                <img src="" alt="" class="bg-gray w-100 d-block"
                                                    id="picture-preview">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-8">
                                            <x-forms.input type="file" name="picture"
                                                onchange="showPreviewImage(event, '#picture-preview')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="nik">Nomor Peserta</x-forms.label>
                                    <x-forms.input type="number" name="nik" min="0000000000000000"
                                        min="9999999999999999" :value="old('nik')" autofocus />
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="email">Alamat Email</x-forms.label>
                                    <x-forms.input type="email" name="email" :value="old('email')" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="name">Nama Lengkap</x-forms.label>
                                    <x-forms.input name="name" :value="old('name')" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="address">Alamat</x-forms.label>
                                    <x-forms.textarea name="address" rows="3">
                                        {{ old('address') }}
                                    </x-forms.textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <x-forms.label id="gender">Jenis Kelamin</x-forms.label>
                                    <x-forms.tom-select name="gender">
                                        <option value="0" @selected(!old('gender'))>Laki-Laki</option>
                                        <option value="1" @selected(old('gender'))>Perempuan</option>
                                    </x-forms.tom-select>
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="place_of_birth">Tempat Lahir</x-forms.label>
                                    <x-forms.input name="place_of_birth" :value="old('place_of_birth')" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="date_of_birth">Tanggal Lahir</x-forms.label>
                                    <x-forms.input name="date_of_birth" class="flatpickr" :value="old('date_of_birth')" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.label id="phone_number">No. Telepon</x-forms.label>
                                    <x-forms.input name="phone_number" :value="old('phone_number')" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <x-action-buttons :index="route('participants.index')" />

            </form>
        </div>
    </div>
@endsection
