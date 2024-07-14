@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="powergrid-table">
            <livewire:tables.trashed-user-table />
        </div>
    </div>
</div>
@endsection