@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-7">
        <form action="{{ route('question-types.store') }}" method="post" novalidate>
            @csrf
            <div class="card">
                <div class="card-header">
                    Form {{ $title }}
                </div>

                <div class="card-body py-4">
                    <div class="mb-3">
                        <x-forms.label id="name">Nama Tipe Soal</x-forms.label>
                        <x-forms.input name="name" :value="old('name')" autofocus />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="question_group_type_id">Tipe Kelompok Soal</x-forms.label>
                        <x-forms.tom-select name="question_group_type_id">
                            @foreach ($questionGroupTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('question_group_type_id')==$type->id)>{{
                                $type->name }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                </div>

            </div>

            <x-action-buttons :index="route('question-types.index')" />

        </form>
    </div>
</div>
@endsection