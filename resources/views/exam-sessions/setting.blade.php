@extends('layouts.app')

@section('buttons')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#create-modal">
                <x-feather name="plus-circle" class="me-2" />
                Buat Baru
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col">

            <div class="card">
                <div class="card-header">
                    Data {{ $title }}
                </div>

                <div class="card-body py-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Tipe Kelompok Soal</th>
                                <th>Jumlah Soal</th>
                                <th>Passing Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examSession->examSessionSettings as $setting)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <x-badge as="a"
                                            :href="route(
                                                'question-group-types.show',
                                                $setting->questionGroupType->id,
                                            )">{{ $setting->questionGroupType->name }}</x-badge>
                                    </td>
                                    <td>
                                        {{ $setting->number_of_question }}
                                    </td>
                                    <td>
                                        {{ $setting->passing_grade }}
                                    </td>
                                    <td>
                                        <x-badge as="a" :href="route('exam-sessions.setting-edit', [
                                            'exam_session' => $examSession,
                                            'exam_session_setting' => $setting,
                                        ])">Edit</x-badge>
                                        <form class="d-inline-block"
                                            action="{{ route('exam-sessions.setting-destroy', [
                                                'exam_session' => $examSession,
                                                'exam_session_setting' => $setting,
                                            ]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-badge as="button" type="submit" color="danger">Hapus</x-badge>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($examSession->examSessionSettings->isEmpty())
                                <tr>
                                    <td colspan="5">
                                        <p class="text-danger p-0 py-2 m-0 fw-bold text-center">Tidak ada data.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($examSession->examSessionSettings->isNotEmpty())
        <div class="row">
            <div class="col">

                <form action="{{ route('exam-sessions.setting-questions', $examSession) }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            Form Penentuan Soal
                        </div>


                        <div class="card-body py-4">
                            @if (session('setting-message'))
                                <x-alert title="Informasi">
                                    {{ session('setting-message') }}
                                </x-alert>
                            @endif
                            @foreach ($examSession->examSessionSettings as $setting)
                                @php
                                    $questionType = $inThisExamSessionQuestionTypes->where('question_group_type_id', $setting->questionGroupType->id)->first();
                                @endphp
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5>{{ $setting->questionGroupType->name }}</h5>
                                        <hr>
                                        <div class="mb-3">
                                            <x-forms.label id="question_ids">Soal</x-forms.label>
                                            <x-forms.tom-select name="question_ids[]"
                                                id="question_ids_{{ $loop->iteration }}" multiple :data="['maxItems' => $setting->number_of_question]">
                                                @foreach ($questionType?->questions ?? [] as $question)
                                                    <option value="{{ $question->id }}" @selected(in_array($question->id, old('question_ids', $examSession->questions->pluck('id')->toArray())))>
                                                        ({{ $question->id }})
                                                        {{ str($question->question_text)->words(7) }} -
                                                        {{ $questionType->name }}</option>
                                                @endforeach
                                            </x-forms.tom-select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <x-button type="submit" color="dark">
                                <x-feather name="save" class="me-1" />
                                Simpan
                            </x-button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @endif


    <form action="{{ route('exam-sessions.setting', $examSession) }}" method="post" novalidate>
        @csrf
        <x-modal modalId="create-modal" :simple="true">

            <x-slot:title>Buat Baru</x-slot:title>

            <div class="mb-3">
                <x-forms.label id="question_group_type_id">Tipe Kelompok Soal</x-forms.label>
                <x-forms.tom-select name="question_group_type_id">
                    @foreach ($questionGroupTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('question_group_type_id') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </x-forms.tom-select>
            </div>

            <div class="mb-3">
                <x-forms.label id="number_of_question">Jumlah Soal</x-forms.label>
                <x-forms.input type="number" name="number_of_question" :value="old('number_of_question', 0)" />
            </div>

            <div class="mb-3">
                <x-forms.label id="passing_grade">Passing Grade</x-forms.label>
                <x-forms.input type="number" name="passing_grade" :value="old('passing_grade', 0)" />
            </div>

            <x-slot:footer>
                <button type="submit" class="btn btn-lg btn-dark w-100 mx-0" data-bs-dismiss="modal">
                    <x-feather name="save" class="me-2" />
                    Simpan
                </button>
            </x-slot:footer>

        </x-modal>
    </form>
@endsection
