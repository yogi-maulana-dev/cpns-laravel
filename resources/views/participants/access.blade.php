@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <form action="{{ route('participants.access', $participant) }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body p-4">
                    {{-- <div class="mb-3">
                        <h6 class="fw-bold">Akses Sesi Ujian Yang Dimiliki Peserta Saat ini</h6>
                        <hr />
                    </div> --}}
                    <div class="list-group-state">
                        <div class="list-group mw-100 w-100 m-0 list-group-radio d-grid gap-2 border-0 mb-4">
                            @foreach ($examSessions as $examSession)
                            <div class="position-relative">
                                <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="checkbox"
                                    name="exam_session_ids[]" id="listGroupRadioGrid{{$loop->index}}"
                                    value="{{ $examSession->id }}" @checked(in_array($examSession->id,
                                $participantExamSessionIds))/>
                                <label class="list-group-item py-3 pe-5" for="listGroupRadioGrid{{$loop->index}}">
                                    <small class="fw-bold text-muted d-block">#{{ $examSession->code }}</small>
                                    <strong class="fw-semibold">{{ $examSession->name }}</strong>
                                    <span class="d-block small opacity-75">{!!
                                        nl2br(str($examSession->description)->words(8))
                                        !!}</span>
                                    <small class="text-muted">{{ $examSession->start_at->format('d/m/y H:i') }} - {{
                                        $examSession->end_at->format('d/m/y H:i') }}</small>
                                </label>
                            </div>
                            @endforeach

                        </div>
                    </div>

                    <x-action-buttons :index="route('participants.index')" for="edit" />
                </div>
            </div>
        </form>
    </div>
</div>
@endsection