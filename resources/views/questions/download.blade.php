@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-lg-7">
        <form action="{{ route('questions.download') }}" method="post">
            @csrf

            <div class="card">
                <div class="card-header">Form {{ $title }}</div>
                <div class="card-body py-4">
                    <div class="mb-3">
                        <x-forms.label id="question_type_ids" :required="false">Tipe Soal</x-forms.label>
                        <x-forms.tom-select name="question_type_ids[]" id="question_type_ids" multiple>
                            @foreach ($questionTypes as $type)
                            <option value="{{ $type->id }}" @selected(in_array($type->
                                id, old('question_type_ids') ?? []))>{{
                                $type->name }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="exam_session_id" :required="false">Sesi Ujian</x-forms.label>
                        <x-forms.tom-select name="exam_session_id" id="exam_session_id">
                            @foreach ($examSessions as $examSession)
                            <option value="{{ $examSession->id }}" @selected($examSession->id ==
                                old('exam_session_id'))>#{{
                                $examSession->code }} - {{
                                $examSession->name }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="start_at" :required="false">Tanggal di Buat (Mulai)</x-forms.label>
                        <x-forms.input class="flatpickr" name="start_at" :value="old('start_at')" />
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="end_at" :required="false">Tanggal di Buat (Sampai)</x-forms.label>
                        <x-forms.input class="flatpickr" name="end_at" :value="old('end_at')" />
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="type" :required="false">Jenis File</x-forms.label>
                        <x-forms.tom-select name="type">
                            @foreach (['EXCEL', 'PDF'] as $type)
                            <option value="{{ $type }}" @selected(old('type', "PDF" )==$type)>{{
                                $type }}</option>
                            @endforeach
                        </x-forms.tom-select>
                    </div>
                    <div class="mb-3">
                        <x-forms.label id="separate_discussion" :required="false">Letak Pembahasan</x-forms.label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="separate_discussion"
                                name="separate_discussion" value="true" />
                            <label class="form-check-label" for="separate_discussion">Pisahkan pembahasan dengan
                                soal</label>
                        </div>
                    </div>
                </div>

            </div>

            <x-action-buttons :index="route('questions.index')" for="custom">
                <li>
                    <button type="submit" class="dropdown-item rounded-2 w-100 mb-1">
                        <x-feather name="download-cloud" class="me-2" />
                        Download
                    </button>
                </li>
            </x-action-buttons>
        </form>

    </div>
</div>
@endsection