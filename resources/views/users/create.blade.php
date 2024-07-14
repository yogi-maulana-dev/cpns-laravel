@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-7 col-lg-8">
        <form action="{{ route('users.store') }}" method="post" novalidate>
            @csrf
            <div class="card">
                <div class="card-header">
                    Form {{ $title }}
                </div>

                <div class="card-body py-4">
                    <div class="mb-3">
                        <x-forms.label id="name">Nama Lengkap User</x-forms.label>
                        <x-forms.input name="name" :value="old('name')" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="email">Email</x-forms.label>
                        <x-forms.input name="email" type="email" :value="old('email')" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="password">Password</x-forms.label>
                        <x-forms.input name="password" type="password" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="password_confirmation">Konfirmasi Password</x-forms.label>
                        <x-forms.input name="password_confirmation" type="password" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="role">Role (Hak Akses)</x-forms.label>
                        <x-forms.tom-select id="role" name="role">
                            @foreach (\App\Enums\UserRole::getListUserCreateRoles() as $role => $text)
                            <option @selected($role==old('role')) value="{{ $role }}">{{ $text }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                </div>

            </div>

            <x-action-buttons :index="route('users.index')" />
        </form>
    </div>
</div>
@endsection