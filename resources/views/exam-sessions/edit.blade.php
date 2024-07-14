@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <form action="{{ route('exam-sessions.update', $examSession) }}" method="post" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Form {{ $title }}
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="name">Nama Sesi Ujian</x-forms.label>
                                <x-forms.input name="name" :value="old('name', $examSession->name)" autofocus />
                            </div>
                            <div class="mb-3">
                                <x-forms.label id="description" :required="false">Keterangan</x-forms.label>
                                <x-forms.textarea name="description" rows="5">
                                    {{old('description', $examSession->description)}}
                                </x-forms.textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Waktu Ujian
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="time">Waktu (Menit)</x-forms.label>
                                <x-forms.input type="number" name="time" :value="old('time', $examSession->time)"
                                    min="0" step="5" />
                            </div>
                            <div class="mb-3">
                                <x-forms.label id="start_at">Tanggal & Waktu Mulai</x-forms.label>
                                <x-forms.input class="flatpickr-custom" data-enable-time="true" data-alt-onput="true"
                                    data-alt-format="d F Y H:i" name="start_at"
                                    :value="old('start_at', $examSession->start_at)" />
                            </div>
                            <div class="mb-3">
                                <x-forms.label id="end_at">Tanggal & Waktu Berakhir</x-forms.label>
                                <x-forms.input class="flatpickr-custom" data-enable-time="true" data-alt-onput="true"
                                    data-alt-format="d F Y H:i" name="end_at"
                                    :value="old('end_at', $examSession->end_at)" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Pengaturan Sesi Ujian
                        </div>

                        <div class="card-body py-4">
                            <div class="mb-3">
                                <x-forms.label id="order_of_question">Urutan Soal</x-forms.label>
                                <x-forms.tom-select name="order_of_question">
                                    @foreach (\App\Enums\OrderOfQuestion::getList() as $id => $text)
                                    <option value="{{ $id }}" @selected(old('order_of_question', $examSession->
                                        order_of_question)==$id)>{{ $text }}
                                    </option>
                                    @endforeach
                                </x-forms.tom-select>
                            </div>
                            <div class="mb-3">
                                <x-forms.label id="result_display_status">Status Hasil Akhir Jawaban</x-forms.label>
                                <x-forms.tom-select name="result_display_status">
                                    @foreach (\App\Enums\ResultDisplayStatus::getList() as $id => $text)
                                    <option value="{{ $id }}" @selected(old('result_display_status', $examSession->
                                        result_display_status)==$id)>{{ $text }}
                                    </option>
                                    @endforeach
                                </x-forms.tom-select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <x-action-buttons :index="route('exam-sessions.index')" for="edit" />

        </form>
    </div>
</div>
@endsection