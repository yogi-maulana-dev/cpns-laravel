@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-7">

        <form action="{{ route('exam-sessions.setting-edit', [
            'exam_session' => $examSession,
            'exam_session_setting' => $examSessionSetting
        ]) }}" method="post" novalidate>
            @csrf
            @method("PUT")
            <div class="card">
                <div class="card-header">
                    Form {{ $title }}
                </div>

                <div class="card-body py-4">

                    <div class="mb-3">
                        <x-forms.label id="question_group_type_id">Tipe Kelompok Soal</x-forms.label>
                        <x-forms.input name="question_group_type_id" disabled
                            :value="$examSessionSetting->questionGroupType->name" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="number_of_question">Jumlah Soal</x-forms.label>
                        <x-forms.input type="number" name="number_of_question"
                            :value="old('number_of_question', $examSessionSetting->number_of_question)" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label id="passing_grade">Passing Grade</x-forms.label>
                        <x-forms.input type="number" name="passing_grade"
                            :value="old('passing_grade', $examSessionSetting->passing_grade)" />
                    </div>

                </div>
            </div>
            <x-action-buttons :index="route('exam-sessions.setting', $examSession)" for="edit" />
        </form>
    </div>
</div>
@endsection