@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">ID</span>
                        <p>{{ $questionType->id }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nama Tipe
                            Soal</span>
                        <p>{{ $questionType->name }}</p>
                    </div>

                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Tipe Kelompok
                            Soal</span>
                        <p>
                            <x-badge as="span" color="primary">{{ $questionType->questionGroupType->name }}</x-badge>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                        <p>
                            <span class="d-block">{{ $questionType->created_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $questionType->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            Pada</span>
                        <p>
                            <span class="d-block">{{ $questionType->updated_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $questionType->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-action-buttons :index="route('question-types.index')" for="show"
            :edit="route('question-types.edit', $questionType)" />
    </div>
</div>
@endsection