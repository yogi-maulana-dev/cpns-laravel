<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\ExamScoreQGT;
use App\Models\ExamSession;
use App\Models\ExamSessionSetting;
use App\Models\ParticipantExamResult;
use App\Models\Question;
use App\Services\ExamSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function __construct(private ExamSessionService $examSessionService)
    {
    }

    public function saveMyAnswer(Request $request, ExamSession $examSession)
    {
        // maybe too many request when exam
        // abort_if(
        //     !$this->examSessionService->haveAccessThis($examSession),
        //     403,
        //     'Anda tidak memiliki akses untuk sesi ujian ini!'
        // );
        // FIX:
        // if (!$examSession->isOpen()) return back()->with('failed', 'Sesi ujian belum dimulai atau sudah berakhir.');

        $validator = Validator::make($request->toArray(), [
            'question_id' => 'required|numeric',
            'answer_id' => 'sometimes|nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $validatedData = $validator->validated();
        $me = auth()->user()->lazyLoadParticipant();
        $questionId = $validatedData['question_id'];

        try {
            DB::beginTransaction();

            $participantExamResult = ParticipantExamResult::query()->where([
                'exam_session_id' => $examSession->id,
                'participant_id' => $me->id,
            ])->firstOrFail();

            if ($participantExamResult->finished_at) {
                return response()->json([
                    'success' => false,
                ]);
            }

            $currentParticipantAnswer = $examSession->participantAnswers()->updateOrCreate([
                'participant_id' => $me->id,
                'question_id' => $validatedData['question_id'],
            ], array_merge([
                'is_ragu' => $request->boolean('is_ragu'),
                'answered_at' => now(),
            ], ($validatedData['answer_id'] ?? false) ? [
                'selected_answer_id' => $validatedData['answer_id'],
            ] : []));

            //
            $examSession->load([
                'participantAnswers' => fn ($query) => $query
                    ->where('participant_id', $me->id),
                'participantAnswers.selectedAnswer',
            ]);

            // [questionTypeId => $questionGroupTypeId]
            $questions = cache()
                ->remember(
                    'questions-'.$examSession->id.'-'.auth()->id(),
                    now()->addMinutes(30),
                    function () use ($examSession) {
                        $questionIds = DB::table('exam_session_question')->where('exam_session_id', $examSession->id)->pluck('question_id');

                        return Question::query()
                            ->with([
                                'questionType' => fn ($query) => $query->select(['id', 'question_group_type_id']),
                            ])
                            ->whereIn('id', $questionIds)
                            ->get();
                    }
                );

            $questionGroupTypeIds = $questions
                ->pluck('questionType.question_group_type_id', 'questionType.id')
                ->toArray();

            $examSessionSettings = cache()
                ->remember(
                    'exam-session-settings-'.$examSession->id.'-'.auth()->id(),
                    now()->addMinutes(30),
                    function () use ($examSession) {
                        return ExamSessionSetting::query()
                            ->where('exam_session_id', $examSession->id)
                            ->get();
                    }
                );

            $passingGradeByQuestionGroupTypeIds = $examSessionSettings
                ->pluck('passing_grade', 'question_group_type_id');

            $correctAnswerCount = 0;
            $unansweredCount = 0;
            $wrongAnswerCount = 0;
            $totalScore = 0;
            $isExamPassed = false;
            $examScoreQGTs = [];

            $answers = cache()
                ->remember(
                    'answers-'.$examSession->id.'-'.auth()->id(),
                    now()->addMinutes(30),
                    fn () => Answer::query()
                        ->whereIn('question_id', $questions->pluck('id'))
                        ->get()
                );

            foreach ($questions as $question) {
                $avalaibleAnswers = $answers->where('question_id', $question->id);

                // handle if the question not answered by participant
                $participantAnswer = $examSession
                    ->participantAnswers
                    ->where('question_id', $question->id)
                    ?->first() ?? null;

                if (! $participantAnswer) {
                    $unansweredCount += 1;
                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => 0,
                        'is_correct' => 0,
                        'is_unanswered' => 1,
                        'is_wrong' => 0,
                    ];

                    continue;
                }

                // correct answer
                $orderIndexCorrectParticipantAnswer = $question->order_index_correct_answer;
                $correctAnswer = $avalaibleAnswers
                    ->where('order_index', $orderIndexCorrectParticipantAnswer)
                    ->first();

                // if participant's answer correct
                if ($participantAnswer->selected_answer_id == $correctAnswer->id) {
                    $correctAnswerCount += 1;
                    $score = $correctAnswer?->weight_score ?? 0;
                    $totalScore += $score;

                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => $score,
                        'is_correct' => 1,
                        'is_unanswered' => 0,
                        'is_wrong' => 0,
                    ];

                    continue;
                } else {
                    $wrongAnswerCount += 1;
                    $score = $participantAnswer?->selectedAnswer?->weight_score ?? 0;

                    $totalScore += $score;

                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => $score,
                        'is_correct' => 0,
                        'is_unanswered' => 0,
                        'is_wrong' => 1,
                    ];

                    continue;
                }
            }

            $examScoreQGTs = collect($examScoreQGTs)
                ->groupBy('question_group_type_id')
                ->map(function (
                    $questionGroupType,
                    $questionGroupTypeIdAsTheKey
                ) use (
                    $passingGradeByQuestionGroupTypeIds,
                    $participantExamResult
                ) {
                    $totalScore = $questionGroupType->sum('total_score');
                    $totalCorrectAnswer = $questionGroupType->sum('is_correct');
                    $totalUnanswer = $questionGroupType->sum('is_unanswered');
                    $totalWrongAnswer = $questionGroupType->sum('is_wrong');

                    return [
                        'participant_exam_result_id' => $participantExamResult->id,
                        'question_group_type_id' => $questionGroupTypeIdAsTheKey,
                        'total_score' => $totalScore,
                        'correct_answer_count' => $totalCorrectAnswer,
                        'unanswered_count' => $totalUnanswer,
                        'wrong_answer_count' => $totalWrongAnswer,
                        'is_passed' => $totalScore >= $passingGradeByQuestionGroupTypeIds[$questionGroupTypeIdAsTheKey],
                    ];
                });

            $isExamPassed = $examScoreQGTs->every(fn ($value, $key) => $value['is_passed']);

            $participantExamResult->update([
                'correct_answer_count' => $correctAnswerCount,
                'unanswered_count' => $unansweredCount,
                'wrong_answer_count' => $wrongAnswerCount,
                'total_exam_time' => $participantExamResult->started_at->diffInSeconds(now()),
                'total_score' => $totalScore,
                'is_passed' => $isExamPassed,
                // 'finished_at' => now(),
            ]);

            foreach ($examScoreQGTs as $examScore) {
                ExamScoreQGT::query()->updateOrCreate(
                    [
                        'participant_exam_result_id' => $examScore['participant_exam_result_id'],
                        'question_group_type_id' => $examScore['question_group_type_id'],
                    ],
                    [
                        'total_score' => $examScore['total_score'],
                        'is_passed' => $examScore['is_passed'],
                        'correct_answer_count' => $examScore['correct_answer_count'],
                        'unanswered_count' => $examScore['unanswered_count'],
                        'wrong_answer_count' => $examScore['wrong_answer_count'],
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'saved_at' => $currentParticipantAnswer['answered_at']->translatedFormat('H:i'),
                'is_ragu' => $currentParticipantAnswer->is_ragu,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            if (! app()->isProduction()) {
                throw $ex;
            }

            return response()->json(array_merge([
                'success' => false,
            ]), 500);
        }
    }

    public function finishMyExam(Request $request, ExamSession $examSession)
    {
        abort_if(
            ! $this->examSessionService->haveAccessThis($examSession),
            403,
            'Anda tidak memiliki akses untuk sesi ujian ini!'
        );

        $me = auth()->user()->lazyLoadParticipant();

        $participantExamResult = ParticipantExamResult::query()->where([
            'exam_session_id' => $examSession->id,
            'participant_id' => $me->id,
        ])->firstOrFail();

        if ($participantExamResult->finished_at) {
            return back()
                ->with('failed', 'Sesi ujian sudah anda selesaikan sebelumnya.');
        }

        $examSession->load([
            'participantAnswers' => fn ($query) => $query
                ->where('participant_id', $me->id),
            'participantAnswers.selectedAnswer',
            'questions.answers',
            'questions.questionType' => fn ($query) => $query->select(['id', 'question_group_type_id']),
            'examSessionSettings',
        ]);

        // [questionTypeId => $questionGroupTypeId]
        $questionGroupTypeIds = $examSession
            ->questions
            ->pluck('questionType.question_group_type_id', 'questionType.id')
            ->toArray();

        $passingGradeByQuestionGroupTypeIds = $examSession
            ->examSessionSettings
            ->pluck('passing_grade', 'question_group_type_id');

        $correctAnswerCount = 0;
        $unansweredCount = 0;
        $wrongAnswerCount = 0;
        $totalScore = 0;
        $isExamPassed = false;
        $examScoreQGTs = [];

        try {
            DB::beginTransaction();
            foreach ($examSession->questions as $question) {
                $avalaibleAnswers = $question->answers;

                // handle if the question not answered by participant
                $participantAnswer = $examSession
                    ->participantAnswers
                    ->where('question_id', $question->id)
                    ?->first() ?? null;

                if (! $participantAnswer) {
                    $unansweredCount += 1;
                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => 0,
                        'is_correct' => 0,
                        'is_unanswered' => 1,
                        'is_wrong' => 0,
                    ];

                    continue;
                }

                // correct answer
                $orderIndexCorrectParticipantAnswer = $question->order_index_correct_answer;
                $correctAnswer = $avalaibleAnswers
                    ->where('order_index', $orderIndexCorrectParticipantAnswer)
                    ->first();

                // if participant's answer correct
                if ($participantAnswer->selected_answer_id == $correctAnswer->id) {
                    $correctAnswerCount += 1;
                    $score = $correctAnswer?->weight_score ?? 0;
                    $totalScore += $score;

                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => $score,
                        'is_correct' => 1,
                        'is_unanswered' => 0,
                        'is_wrong' => 0,
                    ];

                    continue;
                } else {
                    $wrongAnswerCount += 1;
                    $score = $participantAnswer?->selectedAnswer?->weight_score ?? 0;

                    $totalScore += $score;

                    $examScoreQGTs[] = [
                        'question_group_type_id' => $questionGroupTypeIds[$question->question_type_id],
                        'total_score' => $score,
                        'is_correct' => 0,
                        'is_unanswered' => 0,
                        'is_wrong' => 1,
                    ];

                    continue;
                }
            }

            $examScoreQGTs = collect($examScoreQGTs)
                ->groupBy('question_group_type_id')
                ->map(function (
                    $questionGroupType,
                    $questionGroupTypeIdAsTheKey
                ) use (
                    $passingGradeByQuestionGroupTypeIds,
                    $participantExamResult
                ) {
                    $totalScore = $questionGroupType->sum('total_score');
                    $totalCorrectAnswer = $questionGroupType->sum('is_correct');
                    $totalUnanswer = $questionGroupType->sum('is_unanswered');
                    $totalWrongAnswer = $questionGroupType->sum('is_wrong');

                    return [
                        'participant_exam_result_id' => $participantExamResult->id,
                        'question_group_type_id' => $questionGroupTypeIdAsTheKey,
                        'total_score' => $totalScore,
                        'correct_answer_count' => $totalCorrectAnswer,
                        'unanswered_count' => $totalUnanswer,
                        'wrong_answer_count' => $totalWrongAnswer,
                        'is_passed' => $totalScore >= $passingGradeByQuestionGroupTypeIds[$questionGroupTypeIdAsTheKey],
                    ];
                });

            $isExamPassed = $examScoreQGTs->every(fn ($value, $key) => $value['is_passed']);

            $participantExamResult->update([
                'correct_answer_count' => $correctAnswerCount,
                'unanswered_count' => $unansweredCount,
                'wrong_answer_count' => $wrongAnswerCount,
                'total_exam_time' => $participantExamResult->started_at->diffInSeconds(now()),
                'total_score' => $totalScore,
                'is_passed' => $isExamPassed,
                'finished_at' => now(),
            ]);

            foreach ($examScoreQGTs as $examScore) {
                ExamScoreQGT::query()->updateOrCreate(
                    [
                        'participant_exam_result_id' => $examScore['participant_exam_result_id'],
                        'question_group_type_id' => $examScore['question_group_type_id'],
                    ],
                    [
                        'total_score' => $examScore['total_score'],
                        'is_passed' => $examScore['is_passed'],
                        'correct_answer_count' => $examScore['correct_answer_count'],
                        'unanswered_count' => $examScore['unanswered_count'],
                        'wrong_answer_count' => $examScore['wrong_answer_count'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('me.exam-sessions.result', $examSession)
                ->with('success', 'Terima kasih untuk anda yang telah mengikuti ujian ini!');
        } catch (\Exception $ex) {
            if (! app()->isProduction()) {
                throw $ex;
            }
            Log::error(json_encode($ex));

            return redirect()
                ->route('me.exam-sessions.exam', $examSession)
                ->with('failed', 'Gagal!, ada masalah yang terjadi pada server.');
        }
    }
}
