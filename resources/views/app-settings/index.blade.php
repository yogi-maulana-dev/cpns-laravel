@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-md-8 col-lg-7">
        <div class="card">

            <form action="{{ route('app-settings.index') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body p-4">
                    <div class="mb-3">
                        <x-forms.label id="web_name">Nama Website</x-forms.label>
                        <x-forms.input name="web_name" :value="old('web_name', $appSetting->web_name)" />
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="web_description">Keterangan Website</x-forms.label>
                        <x-forms.textarea name="web_description" rows="5">
                            {{ old('web_description', $appSetting->web_description) }}
                        </x-forms.textarea>
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="footer">Footer Website</x-forms.label>
                        <x-forms.textarea name="footer" rows="1">
                            {{ old('footer', $appSetting->footer) }}
                        </x-forms.textarea>
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="logo">Logo</x-forms.label>
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                <div class="bg-light border rounded overflow-hidden d-grid"
                                    style="min-height: 50px; height: 100%; place-content: center;">
                                    {{-- <span data-feather="slash"></span> --}}
                                    <img src="{{ asset('storage/' . $appSetting->logo) }}" alt=""
                                        class="bg-gray w-100 d-block" id="img-preview">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <x-forms.input type="file" name="logo"
                                    onchange="showPreviewImage(event, '#img-preview')" />
                            </div>
                        </div>
                        @if ($appSetting->logo)
                        <x-badge as="a" :href="route('app-settings.destroy-image', ['type' => 'logo'])" color="danger"
                            class="">
                            Hapus
                            logo</x-badge>
                        @endif
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="logo_icon">Logo Icon</x-forms.label>
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                <div class="bg-light border rounded overflow-hidden d-grid"
                                    style="min-height: 50px; height: 100%; place-content: center;">
                                    {{-- <span data-feather="slash"></span> --}}
                                    <img src="{{ asset('storage/' . $appSetting->logo_icon) }}" alt=""
                                        class="bg-gray w-100 d-block" id="img-preview-icon">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <x-forms.input type="file" name="logo_icon"
                                    onchange="showPreviewImage(event, '#img-preview-icon')" />
                                <small class="text-muted d-block mt-1">Gambar akan muncul di bagian icon
                                    tab.</small>
                            </div>
                        </div>
                        @if ( $appSetting->logo_icon)
                        <x-badge as="a" :href="route('app-settings.destroy-image', ['type' => 'logo_icon'])"
                            color="danger" class="">
                            Hapus
                            logo icon</x-badge>
                        @endif
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="login_background">Login Background</x-forms.label>
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                <div class="bg-light border rounded overflow-hidden d-grid"
                                    style="min-height: 50px; height: 100%; place-content: center;">
                                    {{-- <span data-feather="slash"></span> --}}
                                    <img src="{{ asset('storage/' . $appSetting->login_background) }}" alt=""
                                        class="bg-gray w-100 d-block" id="img-preview-login-background">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <x-forms.input type="file" name="login_background"
                                    onchange="showPreviewImage(event, '#img-preview-login-background')" />
                            </div>
                        </div>
                        @if ($appSetting->login_background)
                        <x-badge as="a" :href="route('app-settings.destroy-image', ['type' => 'login_background'])"
                            color="danger" class="">Hapus
                            login background</x-badge>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <x-button color="dark" type="submit">
                            <x-feather name="save" class="align-text-middle me-1" />
                            Simpan
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection