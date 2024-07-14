@extends('layouts.app')

@section('buttons')
<div class="btn-toolbar mb-2 mb-md-0">
    <div>
        <a href="{{ route('exam-sessions.live-results', $examSession) }}" class="btn btn-dark">
            <x-feather name="activity" class="me-2" />
            Live
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="powergrid-table">
            <livewire:tables.participant-exam-result-table :examSessionId="$examSession->id" />
        </div>
    </div>
</div>
@endsection