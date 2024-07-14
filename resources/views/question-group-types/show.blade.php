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
                        <p>{{ $questionGroupType->id }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Nama Tipe
                            Kelompok Soal</span>
                        <p>{{ $questionGroupType->name }}</p>
                    </div>

                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Dibuat Pada</span>
                        <p>
                            <span class="d-block">{{ $questionGroupType->created_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $questionGroupType->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-bolder mb-2 d-block text-uppercase text-decoration-underline">Diperbarui
                            Pada</span>
                        <p>
                            <span class="d-block">{{ $questionGroupType->updated_at->translatedFormat('d F Y
                                H:i') }}</span>
                            <span>({{
                                $questionGroupType->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-action-buttons :index="route('question-group-types.index')" for="show"
            :edit="route('question-group-types.edit', $questionGroupType)" />
    </div>
</div>
@endsection