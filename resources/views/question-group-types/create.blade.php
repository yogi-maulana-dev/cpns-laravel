@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-7">
        <form action="{{ route('question-group-types.store') }}" method="post" novalidate>
            @csrf
            <div class="card">
                <div class="card-header">
                    Form {{ $title }}
                </div>

                <div class="card-body py-4">
                    <div class="mb-3">
                        <x-forms.label id="name">Nama Tipe Kelompok Soal</x-forms.label>
                        <x-forms.input name="name" :value="old('name')" autofocus />
                    </div>

                </div>

            </div>

            <x-action-buttons :index="route('question-group-types.index')" />

        </form>
    </div>
</div>
@endsection