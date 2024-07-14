@extends('layouts.app')

@section('buttons')
<div class="btn-toolbar mb-2 mb-md-0">
    <div>
        <a href="{{ route('question-types.create') }}" class="btn btn-dark">
            <x-feather name="plus-circle" class="me-2" />
            Buat Baru
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="powergrid-table">
            <livewire:tables.question-type-table />
        </div>
    </div>
</div>
@endsection