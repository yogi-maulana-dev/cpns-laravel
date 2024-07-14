@extends('layouts.exam')

@section('content')
    <div class="row" x-data="{ activeQuestionIndex: 0, activeQuestionId: {{ $questions?->first()?->id }}, questionIds: @json($questions->pluck('id')->toArray()), selectedQuestionIds: [], raguQuestionIds: @json($examSession->participantAnswers->where('is_ragu', true)->pluck('question_id')) }">
        <div class="col-xl-8 mb-3">
            @if ($questions->isEmpty())
                <p class="text-center fw-bold text-danger py-5">Soal tidak ada!</p>
            @else
                @foreach ($questions as $question)
                    @php
                        $parentIteration = $loop->iteration;
                    @endphp
                    <div class="mb-2" x-show="{{ $question->id }} == activeQuestionId">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                {{-- <span
                                    class="text-bg-dark d-inline-block mb-2 py-1 px-3 rounded exam-question-iteration-badge">{{ $parentIteration }}</span>
                                <span
                                    class="text-bg-dark d-inline-block mb-2 py-1 px-3 rounded exam-question-iteration-badge">{{ $question->questionType->questionGroupType->name }}</span> --}}
                                <span class="text-sm text-bg-light border d-inline-block mb-2 py-1 px-3 rounded d-none"
                                    id="status-saved-question-{{ $question->id }}"></span>
                            </div>
                            <div>
                                <x-badge as="button" color="success" data-is-ragu="false"
                                    x-on:click="
                    $el.disabled = true;
                    await sendRaguAnswerRequest($el, {{ $question->id }}, '{{ route('me.exams.save-answer', $examSession) }}');
                    raguQuestionIds = raguQuestionIds.filter(id => id != {{ $question->id }})
                    $el.disabled = false;
                    "
                                    x-show="raguQuestionIds.find(rqi => rqi == {{ $question->id }}) && selectedQuestionIds.find(rqi => rqi == {{ $question->id }})">
                                    Tidak Ragu
                                </x-badge>
                                <x-badge as="button" color="warning" data-is-ragu="true"
                                    x-on:click="
                    $el.disabled = true;
                    await sendRaguAnswerRequest($el, {{ $question->id }}, '{{ route('me.exams.save-answer', $examSession) }}');
                    raguQuestionIds.push({{ $question->id }});
                    $el.disabled = false;
                    "
                                    x-show="!raguQuestionIds.find(rqi => rqi == {{ $question->id }}) && selectedQuestionIds.find(rqi => rqi == {{ $question->id }})">
                                    Ragu
                                </x-badge>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                @if ($question->question_image)
                                    <div
                                        class="d-flex align-items-start justify-content-between flex-column flex-md-row gap-3">
                                        <a href="{{ asset(' storage/' . $question->question_image) }}"
                                            class="exam-question-image-parent">
                                            <img src="{{ asset('storage/' . $question->question_image) }}"
                                                alt="Gambar Soal Ujian" class="img-thumbnail w-100">
                                        </a>
                                        <div>
                                            <p>{!! nl2br($question->question_text) !!}</p>
                                        </div>
                                    </div>
                                @else
                                    <p>{!! nl2br($question->question_text) !!}</p>
                                @endif
                                <hr />
                                <div class="exam-answer-options list-group list-group-checkable d-grid gap-2 border-0">
                                    @foreach ($question->answers as $answer)
                                        @php
                                            $wasSelected =
                                                $examSession->participantAnswers
                                                    ->where('selected_answer_id', $answer->id)
                                                    ->where('question_id', $question->id)
                                                    ?->first() ?? false;
                                        @endphp
                                        <div class="d-flex gap-2 align-items-start justify-content-start">
                                            <div class="text-center">
                                                <span
                                                    class="text-bg-dark mb-2 py-1 px-2 rounded text-sm d-block">{{ chr(64 + $answer->order_index) }}</span>
                                                @if ($answer->answer_image)
                                                    <a href="{{ asset('storage/' . $answer->answer_image) }}"
                                                        class="d-block link-dark">
                                                        <x-feather name="image" />
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <input class="list-group-item-check pe-none" type="radio"
                                                    name="answer-option[{{ $question->id }}]"
                                                    value="{{ $answer->order_index }}"
                                                    id="answer-option{{ $parentIteration }}-{{ $loop->iteration }}"
                                                    data-question-id="{{ $question->id }}"
                                                    data-answer-id="{{ $answer->id }}" @checked($wasSelected)
                                                    @if ($wasSelected) x-init="selectedQuestionIds.push({{ $wasSelected->question_id }})" @endif
                                                    x-on:change="selectedQuestionIds.push({{ $question->id }}); sendAnswerRequest($el, '{{ route('me.exams.save-answer', $examSession) }}');" />
                                                <label class="list-group-item rounded-3 py-3"
                                                    for="answer-option{{ $parentIteration }}-{{ $loop->iteration }}">
                                                    @if ($answer->answer_image)
                                                        <div
                                                            class="d-flex align-items-start justify-content-between flex-column flex-md-row gap-3">
                                                            <div class="exam-answer-image-parent">
                                                                <img src="{{ asset('storage/' . $answer->answer_image) }}"
                                                                    alt="Gambar Soal Ujian" class="img-thumbnail w-100">
                                                            </div>
                                                            <div class="d-block w-100">
                                                                <p>{!! nl2br($answer->answer_text) !!}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {!! nl2br($answer->answer_text) !!}
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <x-button color="danger"
                        x-on:click="
                    activeQuestionIndex -= 1;
                    activeQuestionId = questionIds[activeQuestionIndex];
                    window.scrollTo({top: 0, behavior: 'smooth'})"
                        x-show="activeQuestionIndex > 0">
                        <x-feather name="arrow-left" class="me-2" />Sebelumnya
                    </x-button>
                </div>
                <div>
                    <x-button color="dark"
                        x-on:click="activeQuestionIndex += 1;
                    activeQuestionId = questionIds[activeQuestionIndex];
                    window.scrollTo({top: 0, behavior: 'smooth'})"
                        x-show="activeQuestionIndex < {{ $questions->count() - 1 }}">
                        Selanjutnya
                        <x-feather class="ms-2" name="arrow-right" />
                    </x-button>
                    <x-button color="success" data-bs-toggle="modal" data-bs-target="#finish-modal"
                        x-show="activeQuestionIndex == {{ $questions->count() - 1 }}">
                        Selesai</x-button>
                </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="fs-5 d-flex align-items-center justify-content-between">
                        <span>Sisa Waktu :</span>
                        <span id="timeleft-of-exam" data-end-time-exam="{{ $participantExamResult->end_at }}">....</span>
                    </div>
                </div>
            </div>
            {{-- <div class="card mb-2">
                <div class="card-body">
                    <img src="{{ asset('storage/' . $participant->picture) }}" alt="Foto Peserta"
                        class="img-thumbnail d-block rounded-circle mx-auto mb-2" style="width: 120px;" />
                    <small class="d-block fw-bold text-muted">NIK</small>
                    <span class="mb-2 d-block">{{ $participant->nik }}</span>
                    <small class="d-block fw-bold text-muted">Nama Lengkap</small>
                    <span class="mb-2 d-block">{{ $participant->name }}</span>
                    <small class="d-block fw-bold text-muted">Tanggal Lahir</small>
                    <span
                        class="mb-2 d-block">{{ $participant->date_of_birth->translatedFormat('d F
                                                                    Y') }}</span>
                    <small class="d-block fw-bold text-muted">Jenis Kelamin</small>
                    <span class="mb-2 d-block">{{ $participant->getGenderText() }}</span>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-body">
                    <div class="m-0 p-0 d-flex flex-wrap gap-2 exam-question-iterations mb-2">
                        @foreach ($questions->pluck('id') as $questionId)
                            <button style="flex-basis: 15%;"
                                class="btn btn-light exam-question-iteration-item text-bg-white border text-center py-2 rounded flex-grow-1"
                                x-on:click="
                        activeQuestionIndex = {{ $loop->index }};
                        activeQuestionId = questionIds[activeQuestionIndex];
                        "
                                x-bind:class="{
                                    'text-bg-dark': selectedQuestionIds.find(id => id == {{ $questionId }}) && !
                                        raguQuestionIds.find(id => id == {{ $questionId }}),
                                    'text-dark border-warning bg-warning-subtle': raguQuestionIds.find(id => id ==
                                        {{ $questionId }})
                                }">{{ $loop->iteration }}</button>
                        @endforeach
                    </div>
                    <x-button color="success" class="w-100" data-bs-toggle="modal" data-bs-target="#finish-modal">
                        <x-feather name="save" class="me-1" />
                        Selesaikan
                        Ujian
                    </x-button>
                </div>
            </div>
        </div>
    </div>


    <form action="{{ route('me.exams.finish', $examSession) }}" method="POST" id="finish-form">
        @csrf
        <x-modal modalId="finish-modal" :simple="true">

            <x-slot:title>Konfirmasi</x-slot:title>

            <x-slot:footer>
                <button type="submit" class="btn btn-lg btn-dark w-100 mx-0" data-bs-dismiss="modal">
                    <x-feather name="send" class="me-2" />
                    Selesai & Simpan Jawaban Saya
                </button>
            </x-slot:footer>

        </x-modal>
    </form>
@endsection

@push('script')
    @vite(['resources/js/pages/exam.js'])
@endpush
