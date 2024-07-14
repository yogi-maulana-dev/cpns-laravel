@extends('layouts.app')

@section('buttons')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div>
            <div class="dropdown">
                <button class="btn btn-dark border dropdown-toggle-no-icon dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <x-feather name="menu" class="me-2" />
                    Opsi
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
                    <li>
                        <a class="dropdown-item rounded-2" href="{{ route('questions.create') }}">
                            <x-feather name="plus-circle" class="align-text-middle me-2" />
                            Buat Baru
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item rounded-2" data-bs-toggle="modal"
                            data-bs-target="#import-modal">
                            <x-feather name="upload" class="align-text-middle me-2" />
                            Import Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2" href="{{ route('questions.download') }}">
                            <x-feather name="folder" class="align-text-middle me-2" />
                            Download Soal
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors?->import?->isNotEmpty())
                <x-alert feather="info" title="Gagal Import Excel">
                    <ul>
                        @foreach ($errors->import->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
            <div class="powergrid-table">
                <livewire:tables.question-table />
            </div>
        </div>
    </div>

    <form action="{{ route('questions.import') }}" method="post" novalidate enctype="multipart/form-data">
        @csrf
        <x-modal modalId="import-modal" :simple="true">

            <x-slot:title>Import Melalui Excel</x-slot:title>

            <div class="mb-2">
                <x-forms.label id="file">File Excel</x-forms.label>
                <x-forms.input type="file" name="file" :value="old('file', 0)" />
            </div>

            <x-badge as="button" type="button" onclick="document.getElementById('template-form').submit()"
                color="success">
                <x-feather name="download" class="me-1" />
                Download Template
            </x-badge>

            <x-slot:footer>
                <button type="submit" class="btn btn-lg btn-dark w-100 mx-0" data-bs-dismiss="modal">
                    <x-feather name="upload" class="me-2" />
                    Import Sekarang
                </button>
            </x-slot:footer>

        </x-modal>
    </form>

    <form action="{{ route('questions.template') }}" method="post" id="template-form" class="d-none">
        @csrf

    </form>
@endsection
