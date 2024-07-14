@extends('layouts.app')

@section('buttons')

@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="powergrid-table">
            <livewire:tables.participant.exam-session-table />
        </div>
    </div>
</div>
@endsection