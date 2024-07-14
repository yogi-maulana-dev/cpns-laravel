<?php

namespace App\Http\Controllers\Participant;

use App\Enums\OrderOfQuestion;
use App\Enums\ResultDisplayStatus;
use App\Helpers\BasicHelper;
use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\ParticipantExamResult;
use App\Services\ExamSessionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function __construct(private ExamSessionService $examSessionService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formLoginExam = request(['code', 'nik', 'requested']);

        $examSession = ExamSession::query()
            ->with('examSessionSettings.questionGroupType')
            ->where('code', $formLoginExam['code'] ?? '')
            ->first();

        $participant = auth()->user()->lazyLoadParticipant();

        if ($formLoginExam['requested'] ?? false) {

            if ($participant->nik != $formLoginExam['nik'] ?? '') {
                return redirect()
                    ->route('me.exam-sessions.index')
                    ->with('failed', 'Nomor Peserta tidak cocok!');
            }

            if (! $examSession || $examSession->code != $formLoginExam['code'] ?? '') {
                return redirect()
                    ->route('me.exam-sessions.index')
                    ->with('failed', 'Nomor sesi ujian tidak cocok!');
            }

            if (! $this->examSessionService->haveAccessThis($examSession)) {
                return redirect()
                    ->route('me.exam-sessions.index')
                    ->with('failed', 'Anda tidak memiliki akses untuk sesi ujian tersebut!');
            }
        }

        return view('me.exam-sessions.index', [
            'title' => 'Sesi Ujian',
            'examSession' => $examSession,
            'participant' => $participant,
        ]);
    }

    public function list()
    {
        return view('me.exam-sessions.list', [
            'title' => 'Sesi Ujian Saya',
        ]);
    }

    public function histories()
    {
        return view('me.exam-sessions.histories', [
            'title' => 'Sejarah Ujian Saya',
        ]);
    }

    public function show(ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        $participant = auth()->user()->lazyLoadParticipant();
        $examSession->load(['examSessionSettings.questionGroupType']);

        $participantExamResult = ParticipantExamResult::query()
            ->where('exam_session_id', $examSession->id)
            ->where('participant_id', $participant->id)
            ->first();

        return view('me.exam-sessions.show', [
            'title' => 'Detail Sesi Ujian',
            'examSession' => $examSession,
            'participant' => $participant,
            'participantExamResult' => $participantExamResult,
        ]);
    }

    public function exam(ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        $examRandomSeed = cache('seed-'.auth()->id());

        $participant = auth()->user()->lazyLoadParticipant();

        $participantExamResult = ParticipantExamResult::query()
            ->where('exam_session_id', $examSession->id)
            ->where('participant_id', $participant->id)
            ->first();

        if (! $examSession->isOpen() && $participantExamResult?->finished_at) {
            return redirect()
                ->route('me.exam-sessions.show', $examSession)
                ->with('failed', 'Sesi ujian belum dimulai atau sudah berakhir.');
        }

        if (! $participantExamResult) {
            return redirect()->route('me.exam-sessions.show', $examSession)
                ->with(
                    'failed',
                    'Mulai sesi ujian dengan menekan tombol "Mulai Ujian Sekarang"'
                );
        }

        if ($participantExamResult->finished_at) {
            return redirect()->route('me.exam-sessions.show', $examSession)
                ->with(
                    'failed',
                    'Sesi ujian sudah anda selesaikan, dan anda tidak dapat mengakses halaman ujian tersebut lagi.'
                );
        }

        $examSession->load([
            'participantAnswers' => fn ($query) => $query
                ->where('participant_id', $participant->id),
            'questions.answers' => fn ($query) => $query->orderBy('order_index'),
            'questions.questionType.questionGroupType',
            'examSessionSettings.questionGroupType',
        ]);

        return view('me.exam-sessions.exam', [
            'title' => sprintf('Ujian #%s - %s', $examSession->code, $examSession->name),
            'examSession' => $examSession,
            'participant' => $participant,
            'questions' => $examSession->order_of_question == OrderOfQuestion::RANDOM() ?
                $examSession->questions->shuffle($examRandomSeed) : $examSession->questions,
            'participantExamResult' => $participantExamResult,
        ]);
    }

    public function examPost(Request $request, ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        if (! $examSession->isOpen()) {
            return back()->with('failed', 'Sesi ujian belum dimulai atau sudah berakhir.');
        }

        $participant = auth()->user()->lazyLoadParticipant();

        $participantExamResult = ParticipantExamResult::query()
            ->where('exam_session_id', $examSession->id)
            ->where('participant_id', $participant->id)
            ->first();

        if ($participantExamResult && $participantExamResult->finished_at) {
            return back()
                ->with(
                    'failed',
                    'Sesi ujian sudah anda selesaikan, dan anda tidak dapat mengakses halaman ini lagi.'
                );
        }

        $examSession->participantExamResults()->firstOrCreate([
            'participant_id' => $participant->id,
        ], [
            'started_at' => now(),
            'end_at' => now()->addMinutes($examSession->time),
        ]);

        return redirect()->route('me.exam-sessions.exam', $examSession)->with('success', 'Selamat ujian!');
    }

    public function result(ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        $participant = auth()->user()->lazyLoadParticipant();

        $participantExamResult = ParticipantExamResult::query()
            ->with('examScoreQGTs')
            ->where('exam_session_id', $examSession->id)
            ->where('participant_id', $participant->id)
            ->first();

        if (! ($participantExamResult && $participantExamResult->finished_at)) {
            return redirect()->route('me.exam-sessions.show', $examSession);
        }

        $examSession->load([
            'examSessionSettings.questionGroupType',
        ]);

        return view('me.exam-sessions.result', [
            'title' => sprintf('Hasil Ujian #%s - %s', $examSession->code, $examSession->name),
            'examSession' => $examSession,
            'participant' => $participant,
            'participantExamResult' => $participantExamResult,
        ]);
    }

    public function downloadResult(Request $request, ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        if ($examSession->result_display_status == ResultDisplayStatus::NOTHING()) {
            return back();
        }

        $participant = auth()->user()->lazyLoadParticipant();

        $participantExamResult = ParticipantExamResult::query()
            ->with('examScoreQGTs')
            ->where('exam_session_id', $examSession->id)
            ->where('participant_id', $participant->id)
            ->first();

        if (! ($participantExamResult && $participantExamResult->finished_at)) {
            return redirect()->route('me.exam-sessions.show', $examSession);
        }

        $examSession->load([
            'questions',
            'participantAnswers' => fn ($query) => $query->where('participant_id', $participant->id),
        ]);

        $pdf = Pdf::loadView('pdfs.result', [
            'examSession' => $examSession,
            'separateDiscussion' => $request->boolean('separate_discussion'),
            'all' => false,
        ]);

        $pdf->set_paper('a4');

        $filename = sprintf('HASIL_UJIAN_#%s_%s_PDF_%s.pdf', $examSession->code, $examSession->name, BasicHelper::dateForFileName());

        return $pdf->stream($filename);
    }
}
