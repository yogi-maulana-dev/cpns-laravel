@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <form action="{{ route('questions.store') }}" method="post" novalidate enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Form Isi Utama Soal
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="question_image" :required="false">Gambar Soal</x-forms.label>
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                        <div class="bg-light border rounded overflow-hidden d-grid"
                                            style="min-height: 50px; height: 100%; place-content: center;">
                                            <img src="" alt="" class="bg-gray w-100 d-block"
                                                id="question-image-preview">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <x-forms.input type="file" name="question_image"
                                            onchange="showPreviewImage(event, '#question-image-preview')" />
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="question_text">Isi Soal</x-forms.label>
                                <x-forms.textarea name="question_text" autofocus>
                                    {{ old('question_text') }}
                                </x-forms.textarea>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="question_type_id">Tipe Soal</x-forms.label>
                                <x-forms.tom-select name="question_type_id">
                                    @foreach ($questionTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('question_type_id')==$type->
                                        id)>{{
                                        $type->name }}</option>
                                    @endforeach
                                </x-forms.tom-select>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="order_index_correct_answer">Jawaban Benar</x-forms.label>
                                <x-forms.tom-select name="order_index_correct_answer">
                                    <?php for ($i = \App\Models\Question::START_ORDER_INDEX; $i <= \App\Models\Question::END_ORDER_INDEX; $i++) : ?>
                                    <option value="{{ $i }}" @selected(old('order_index_correct_answer')==$i)>{{
                                        chr(64 + $i) }}</option>
                                    <?php endfor; ?>
                                </x-forms.tom-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Form Pembahasan Soal
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="discussion_image" :required="false">Gambar Pembahasan</x-forms.label>
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                        <div class="bg-light border rounded overflow-hidden d-grid"
                                            style="min-height: 50px; height: 100%; place-content: center;">
                                            <img src="" alt="" class="bg-gray w-100 d-block"
                                                id="discussion-image-preview">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <x-forms.input type="file" name="discussion_image"
                                            onchange="showPreviewImage(event, '#discussion-image-preview')" />
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="discussion" :required="false">Pembahasan</x-forms.label>
                                <x-forms.textarea name="discussion" rows="5">
                                    {{ old('discussion') }}
                                </x-forms.textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row">
                <?php for ($i = \App\Models\Question::START_ORDER_INDEX; $i <= \App\Models\Question::END_ORDER_INDEX; $i++) : ?>
                <div class="col-md-6">
                    <input type="hidden" name="answers[{{ $i }}][order_index]" value="{{$i}}" />
                    <div class="card mb-3" style="scroll-snap-align: start;">
                        <div class="card-header">
                            Form Jawaban {{ chr(64 + $i) }}
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="answers[{{ $i }}][answer_image]" :required="false">Gambar Jawaban
                                </x-forms.label>
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                                        <div class="bg-light border rounded overflow-hidden d-grid"
                                            style="min-height: 50px; height: 100%; place-content: center;">
                                            <img src="" alt="" class="bg-gray w-100 d-block"
                                                id="answer-image-preview-{{ $i }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <x-forms.input type="file" name="answers[{{ $i }}][answer_image]"
                                            error="answers.{{$i}}.answer_image"
                                            onchange="showPreviewImage(event, '#answer-image-preview-{{ $i }}')" />
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="answers[{{ $i }}][answer_text]">Isi Jawaban</x-forms.label>
                                <x-forms.textarea name="answers[{{ $i }}][answer_text]"
                                    error="answers.{{$i}}.answer_text">
                                    {{ old('answers')[$i]['answer_text'] ?? '' }}
                                </x-forms.textarea>
                            </div>

                            <div class="mb-3">
                                <x-forms.label id="answers[{{ $i }}][weight_score]">Bobot Nilai</x-forms.label>
                                <x-forms.input type="number" name="answers[{{ $i }}][weight_score]"
                                    error="answers.{{$i}}.weight_score"
                                    :value="old('answers')[$i]['weight_score'] ?? ''" />
                            </div>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <x-action-buttons :index="route('questions.index')" />

        </form>
    </div>
</div>
@endsection