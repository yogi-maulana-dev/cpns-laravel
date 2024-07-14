@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-7 col-lg-8">
        <form action="{{ route('users.update', $user->id) }}" method="post" novalidate>
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    Form {{ $title }}
                </div>

                <div class="card-body py-4">
                    <div class="mb-3">
                        <x-forms.label id="name">Nama Lengkap User</x-forms.label>
                        <x-forms.input name="name" :value="old('name', $user->name)" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="email">Email</x-forms.label>
                        <x-forms.input name="email" type="email" :value="old('email', $user->email)" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="password" :required="false">Password</x-forms.label>
                        <x-forms.input name="password" type="password" />
                        <small class="text-muted mt-2 d-block">
                            <span data-feather="info" class="align-text-middle me-1"></span>
                            Jika password di-isi, maka akan menggantikan password yang lama.
                        </small>
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="password_confirmation" :required="false">Konfirmasi Password</x-forms.label>
                        <x-forms.input name="password_confirmation" type="password" />
                    </div>

                    @if ($user->isSuperadmin() || $user->isOperatorUjian() || $user->isOperatorSoal())
                    <div class="mb-3">
                        <x-forms.label id="role">Role (Hak Akses)</x-forms.label>
                        <x-forms.tom-select id="role" name="role">
                            @foreach (\App\Enums\UserRole::getListUserCreateRoles() as $role => $text)
                            <option @selected($role==old('role', $user->role)) value="{{ $role }}">{{ $text }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                    @endif
                </div>

            </div>

            <x-action-buttons :index="route('users.index')" for="edit" />
        </form>
    </div>
</div>
@endsection