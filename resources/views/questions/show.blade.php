@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <div class="card mb-3">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">ID</span>
                        <p>{{ $question->id }}</p>
                    </div>
                    <div class="col-sm-12">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Isi Soal
                            @if ($question->question_image)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#question-image-modal">
                                <x-feather name="image" />
                            </a>
                            @endif
                        </span>
                        <p class="text-bg-light p-3 border rounded">{!! nl2br($question->question_text) !!}</p>
                    </div>

                    <div class="col-sm-12">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Pembahasan
                            @if ($question->discussion_image)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#discussion-image-modal">
                                <x-feather name="image" />
                            </a>
                            @endif
                        </span>
                        <p class="text-bg-light p-3 border rounded">{!! nl2br($question->discussion) !!}</p>
                    </div>

                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tipe Soal</span>
                        <p>
                            <x-badge as="a" :href="route('question-types.show', $question->question_type_id)">{{
                                $question->questionType->name }}</x-badge>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Jawaban
                            Benar</span>
                        <p>
                            {{ chr(64 + $question->order_index_correct_answer) }}
                        </p>
                    </div>

                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                        <p>
                            <span class="d-block">{{ $question->created_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $question->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            Pada</span>
                        <p>
                            <span class="d-block">{{ $question->updated_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $question->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Pilihan Jawaban</div>
            <div class="card-body py-4">
                <div class="row">
                    @foreach ($question->answers as $answer)
                    <div class="col-sm-6">
                        <div class="card mb-2">
                            <div class="card-body">
                                <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Isi
                                    Jawaban {{ chr(64 + $answer->order_index) }} @if ($answer->answer_image)
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#answer-image-{{ $answer->order_index }}-modal">
                                        <x-feather name="image" />
                                    </a>
                                    @endif</span>
                                <p>{{ $answer->answer_text }}</p>
                                <h5 class="text-success">{{ $answer->weight_score }}</h5>
                            </div>
                        </div>
                    </div>

                    <x-modal modalId="answer-image-{{ $answer->order_index }}-modal" :simple="true">

                        <x-slot:title>Gambar Jawaban {{ chr(64 + $answer->order_index) }}</x-slot:title>

                        <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="img-thumbnail" />

                        <x-slot:footer></x-slot:footer>

                    </x-modal>
                    @endforeach
                </div>
            </div>
        </div>

        <x-action-buttons :index="route('questions.index')" for="show" :edit="route('questions.edit', $question)" />
    </div>
</div>

<x-modal modalId="question-image-modal" :simple="true">

    <x-slot:title>Gambar Soal</x-slot:title>

    <img src="{{ asset('storage/' . $question->question_image) }}" alt="" class="img-thumbnail" />

    <x-slot:footer></x-slot:footer>

</x-modal>

<x-modal modalId="discussion-image-modal" :simple="true">

    <x-slot:title>Gambar Pembahasan</x-slot:title>

    <img src="{{ asset('storage/' . $question->discussion_image) }}" alt="" class="img-thumbnail" />

    <x-slot:footer></x-slot:footer>

</x-modal>
@endsection