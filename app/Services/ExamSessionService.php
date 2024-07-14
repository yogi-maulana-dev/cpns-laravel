<?php

namespace App\Services;

use App\Models\ExamSession;
use Illuminate\Support\Facades\DB;

class ExamSessionService
{
    public function haveAccessThis(ExamSession $examSession)
    {
        $participantIds = DB::table('exam_session_participant')
            ->where('exam_session_id', $examSession->id)
            ->pluck('participant_id')
            ->toArray();

        return in_array(auth()->user()->lazyLoadParticipant()->id, $participantIds);
    }

    public function loadParticipantExamResult(ExamSession $examSession, $participantId)
    {
        $examSession->load([
            'participantExamResults' => fn ($query) => $query->where('participant_id', $participantId),
        ]);
    }

    public function isFinished(ExamSession $examSession)
    {
        if ($examSession->participantExamResults->isNotEmpty()) {
            $examResultOfCurrentParticipant = $examSession->participantExamResults->first();

            return $examResultOfCurrentParticipant->finished_at;
        }

        return false;
    }
}
