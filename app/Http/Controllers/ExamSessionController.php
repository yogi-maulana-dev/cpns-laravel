<?php

namespace App\Http\Controllers;

use App\Helpers\BasicHelper;
use App\Http\Requests\ExamSessionRequest;
use App\Http\Requests\ExamSessionSettingRequest;
use App\Models\ExamSession;
use App\Models\ExamSessionSetting;
use App\Models\ParticipantAnswer;
use App\Models\ParticipantExamResult;
use App\Models\Question;
use App\Models\QuestionGroupType;
use App\Models\QuestionType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSessionController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('exam-sessions.index', [
            'title' => 'Data Sesi Ujian',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exam-sessions.create', [
            'title' => 'Tambah Data Sesi Ujian Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ExamSessionRequest $request)
    {
        ExamSession::create(array_merge($request->validated(), [
            'created_by' => auth()->id(),
            'code' => ExamSession::getNewCode(),
        ]));

        $route = $request->boolean('no-redirect') ?
            'exam-sessions.create' : 'exam-sessions.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSession $examSession)
    {
        $examSession->load(['createdBy', 'lastUpdatedBy']);

        return view('exam-sessions.show', [
            'title' => 'Detail Data Sesi Ujian',
            'examSession' => $examSession,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamSession $examSession)
    {
        return view('exam-sessions.edit', [
            'title' => 'Edit Data Sesi Ujian',
            'examSession' => $examSession,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ExamSessionRequest $request, ExamSession $examSession)
    {
        $examSession->update(array_merge($request->validated(), [
            'last_updated_by' => auth()->id(),
        ]));

        return redirect()
            ->route('exam-sessions.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function setting(ExamSession $examSession)
    {
        $examSession->load([
            'examSessionSettings' => fn ($query) => $query->orderByDesc('created_at'),
            'examSessionSettings.questionGroupType',
            'questions:id',
        ]);

        $questionGroupTypeIds = $examSession->examSessionSettings->pluck('question_group_type_id');

        $inThisExamSessionQuestionTypes = QuestionType::with('questions')
            ->whereIn('question_group_type_id', $questionGroupTypeIds)->get();

        return view('exam-sessions.setting', [
            'title' => 'Pengaturan Sesi Ujian',
            'examSession' => $examSession,
            'questionGroupTypes' => QuestionGroupType::orderBy('name')->get(),
            'inThisExamSessionQuestionTypes' => $inThisExamSessionQuestionTypes,
        ]);
    }

    public function settingPost(ExamSessionSettingRequest $request, ExamSession $examSession)
    {
        $examSession->load('examSessionSettings');

        $alreadyInsertedQuestionGroupTypeIds = $examSession->examSessionSettings->pluck('question_group_type_id')->toArray();

        if (in_array($request->question_group_type_id, $alreadyInsertedQuestionGroupTypeIds)) {
            return redirect()
                ->route('exam-sessions.setting', $examSession)
                ->with('failed', 'Data gagal disimpan, karena tipe kelompok soal sudah ada. Silahkan edit data saja!');
        }

        $examSession->examSessionSettings()->create($request->validated());

        return redirect()
            ->route('exam-sessions.setting', $examSession)
            ->with('success', 'Data berhasil disimpan.');
    }

    // TODO: implement delete setting
    public function settingDestroy(
        Request $request,
        ExamSession $examSession,
        ExamSessionSetting $examSessionSetting
    ) {

        try {
            DB::beginTransaction();

            $questionTypeIds = QuestionType::query()
                ->where('question_group_type_id', $examSessionSetting->question_group_type_id)
                ->pluck('id');

            $examSessionSetting->delete();

            $questionIds = Question::query()
                ->whereIn('question_type_id', $questionTypeIds)
                ->pluck('id');
            DB::table('exam_session_question')
                ->whereIn('question_id', $questionIds)
                ->where('exam_session_id', $examSession->id)
                ->delete();

            DB::commit();
        } catch (\Exception $ex) {
            return redirect()
                ->route('exam-sessions.setting', $examSession)
                ->with('danger', 'Data gagal dihapus.');
        }

        return redirect()
            ->route('exam-sessions.setting', $examSession)
            ->with('success', 'Data berhasil dihapus.');
    }

    public function settingQuestionsPost(Request $request, ExamSession $examSession)
    {
        // fix: for more secure, we must implements some validations:
        // 1. Check if the setting exam session there question types
        $examSession->questions()->sync($request->question_ids ?? []);

        session()->remove('setting-message');

        return redirect()
            ->route('exam-sessions.setting', $examSession)
            ->with('success', 'Data penentuan soal berhasil disimpan.');
    }

    public function settingEdit(
        ExamSession $examSession,
        ExamSessionSetting $examSessionSetting
    ) {
        $examSessionSetting->load('questionGroupType');

        return view('exam-sessions.setting-edit', [
            'title' => 'Edit Pengaturan Sesi Ujian',
            'examSession' => $examSession,
            'examSessionSetting' => $examSessionSetting,
        ]);
    }

    public function settingUpdate(
        Request $request,
        $examSessionId,
        ExamSessionSetting $examSessionSetting
    ) {
        $validatedData = $this->validate($request, [
            'number_of_question' => ['required', 'min:0'],
            'passing_grade' => ['required', 'min:0'],
        ], attributes: [
            'number_of_question' => 'Jumlah Soal',
            'passing_grade' => 'Passing Grade',
        ]);

        $lastNumberOfQuestion = $examSessionSetting->number_of_question;

        $examSessionSetting->update($validatedData);

        if ($lastNumberOfQuestion != $validatedData['number_of_question']) {
            session()->put('setting-message', 'Silahkan simpan data penentuan soal agar singkron dengan pengaturan soal!');
        }

        return redirect()
            ->route('exam-sessions.setting', [
                'exam_session' => $examSessionId,
            ])
            ->with('success', 'Data pengaturan berhasil disimpan.');
    }

    public function participantResults(ExamSession $examSession)
    {
        return view('exam-sessions.results', [
            'title' => 'Data Hasil Peserta Sesi Ujian',
            'examSession' => $examSession,
        ]);
    }

    public function liveParticipantResults(ExamSession $examSession)
    {
        if (request()->isJson()) {
        }

        return view('exam-sessions.live-results', [
            'title' => 'LIVE: Hasil Peserta Sesi Ujian',
            'examSession' => $examSession,
        ]);
    }

    public function jsonLiveParticipantResults(ExamSession $examSession)
    {
        $answers = ParticipantAnswer::query()
            ->select(['id', 'participant_id'])
            ->where('exam_session_id', $examSession->id)
            ->get();

        $answers = $answers->groupBy('participant_id')->map(fn ($item) => count($item))->toArray();

        $results = ParticipantExamResult::query()
            ->select(['participant_id', 'id', 'total_score', 'unanswered_count'])
            ->where('exam_session_id', $examSession->id)
            ->with([
                'participant:id,name,nik',
                'examScoreQGTs.questionGroupType' => fn ($query) => $query->orderByDesc('id'),
            ])
            ->get();

        $results->each(function ($result) use ($answers) {
            $result->scores = $result->examScoreQGTs->groupBy('questionGroupType.name');
            $result->scores = $result->scores->map(fn ($score) => $score->sum('total_score') ?? 0);
            $result->answered_count = ($answers[$result->participant_id] ?? false) ? $answers[$result->participant_id] : 0;
        });

        $examSession->loadCount('questions');

        $results = array_map(function ($result) {
            unset($result['exam_score_q_g_ts']);

            return $result;
        }, $results->toArray());

        return response()->json(
            [
                'columns' => count($results) > 0 ? array_keys($results[0]['scores']->toArray()) : false,
                'data' => $results,
                'questions_count' => $examSession->questions_count,
            ]
        );
    }

    public function participantResult(ExamSession $examSession, $participantExamResultId)
    {
        $examSession->load([
            'examSessionSettings.questionGroupType',
        ]);

        $participantExamResult = ParticipantExamResult::query()
            ->with([
                'examScoreQGTs',
                'participant',
            ])->find($participantExamResultId);

        if (! ($participantExamResult && $participantExamResult->finished_at)) {
            return back()
                ->with(
                    'failed',
                    'Sesi ujian belum selesai.'
                );
        }

        return view('exam-sessions.result', [
            'title' => 'Detail Hasil Peserta Sesi Ujian',
            'examSession' => $examSession,
            'participant' => $participantExamResult->participant,
            'participantExamResult' => $participantExamResult,
        ]);
    }

    public function downloadParticipantResult(Request $request, ExamSession $examSession, ParticipantExamResult $participantExamResult)
    {
        $examSession->load([
            'questions',
            'participantAnswers' => fn ($query) => $query->where('participant_id', $participantExamResult->participant_id),
        ]);

        $participantExamResult = ParticipantExamResult::query()
            ->with([
                'examScoreQGTs',
                'participant',
            ])->find($participantExamResult->id);

        if (! ($participantExamResult && $participantExamResult->finished_at)) {
            return back()
                ->with(
                    'failed',
                    'Sesi ujian belum selesai.'
                );
        }

        $pdf = Pdf::loadView('pdfs.result', [
            'examSession' => $examSession,
            'separateDiscussion' => $request->boolean('separate_discussion'),
            'all' => true,
        ]);

        $pdf->set_paper('a4');

        $filename = sprintf('HASIL_UJIAN_#%s_%s_PDF_%s.pdf', $examSession->code, $examSession->name, BasicHelper::dateForFileName());

        return $pdf->stream($filename);
    }
}
