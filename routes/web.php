<?php

use App\Enums\UserRole;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamSessionController;
use App\Http\Controllers\LogLoginController;
use App\Http\Controllers\Participant\ExamController;
use App\Http\Controllers\Participant\ExamSessionController as ParticipantExamSessionController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionDownloadController;
use App\Http\Controllers\QuestionGroupTypeController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', auth()->check() ? '/dashboard' : '/login');

Route::middleware([
    'auth',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::middleware('role:' . UserRole::PARTICIPANT())->group(function () {
        Route::get('/me/exam-sessions', [ParticipantExamSessionController::class, 'index'])
            ->name('me.exam-sessions.index');
        Route::get('/me/exam-sessions/list', [ParticipantExamSessionController::class, 'list'])
            ->name('me.exam-sessions.list');
        Route::get('/me/exam-sessions/histories', [ParticipantExamSessionController::class, 'histories'])
            ->name('me.exam-sessions.histories');
        Route::get('/me/exam-sessions/{exam_session:code}', [ParticipantExamSessionController::class, 'show'])
            ->name('me.exam-sessions.show');
        Route::get('/me/exam-sessions/{exam_session:code}/exam', [
            ParticipantExamSessionController::class, 'exam',
        ])->name('me.exam-sessions.exam');
        Route::get('/me/exam-sessions/{exam_session:code}/result', [
            ParticipantExamSessionController::class, 'result',
        ])->name('me.exam-sessions.result');
        Route::post('/me/exam-sessions/{exam_session:code}/result', [
            ParticipantExamSessionController::class, 'downloadResult',
        ]);

        Route::post('/me/exam-sessions/{exam_session:code}/exam', [ParticipantExamSessionController::class, 'examPost']);
        Route::post('/me/exams/{exam_session:code}/save', [ExamController::class, 'saveMyAnswer'])->name('me.exams.save-answer');
        Route::post('/me/exams/{exam_session:code}/finish', [
            ExamController::class, 'finishMyExam',
        ])->name('me.exams.finish');
    });

    Route::middleware('role:' . implode(',', [
        UserRole::SUPERADMIN(),
        UserRole::OPERATOR_UJIAN()
    ]))->group(function () {

        Route::post('/participants/active', [
            ParticipantController::class, 'active',
        ])->name('participants.active');

        Route::post('/participants/sync', [
            ParticipantController::class, 'sync',
        ])->name('participants.sync');

        Route::post('/participants/import', [
            ParticipantController::class, 'import',
        ])->name('participants.import');

        Route::post('/participants/template', [
            ParticipantController::class, 'template',
        ])->name('participants.template');

        Route::get('/participants/{participant}/access', [
            ParticipantController::class, 'participantExamSessionAccess',
        ])->name('participants.access');
        Route::post('/participants/{participant}/access', [
            ParticipantController::class, 'participantExamSessionAccessPost',
        ]);

        Route::resource('participants', ParticipantController::class)
            ->except('destroy');
    });

    Route::middleware('role:' . implode(',', [UserRole::SUPERADMIN(), UserRole::OPERATOR_SOAL()]))->group(function () {
        Route::resource('question-group-types', QuestionGroupTypeController::class)
            ->except('destroy');

        Route::resource('question-types', QuestionTypeController::class)
            ->except('destroy');

        Route::post('/questions/import', [
            QuestionController::class, 'import',
        ])->name('questions.import');
        Route::get('/questions/download', [
            QuestionDownloadController::class, 'index',
        ])->name('questions.download');
        Route::post('/questions/download', [
            QuestionDownloadController::class, 'download',
        ]);
        Route::post('/questions/template', [
            QuestionController::class, 'template',
        ])->name('questions.template');
        Route::resource('questions', QuestionController::class)
            ->except('destroy');
    });

    Route::middleware('role:' . UserRole::SUPERADMIN())->group(function () {

        Route::get('/users/trash', [UserController::class, 'trash'])
            ->name('users.trash');

        Route::resource('users', UserController::class)
            ->except('destroy');

        Route::resource('exam-sessions', ExamSessionController::class)
            ->except('destroy');

        Route::get('/exam-sessions/{exam_session}/results', [ExamSessionController::class, 'participantResults'])
            ->name('exam-sessions.results');
        Route::get('/exam-sessions/{exam_session}/live-results', [ExamSessionController::class, 'liveParticipantResults'])
            ->name('exam-sessions.live-results');
        Route::get('/exam-sessions/{exam_session}/json-live-results', [ExamSessionController::class, 'jsonLiveParticipantResults'])
            ->name('exam-sessions.json-live-results');
        Route::get('/exam-sessions/{exam_session}/results/{participant_exam_result}/result', [ExamSessionController::class, 'participantResult'])
            ->name('exam-sessions.result');
        Route::post('/exam-sessions/{exam_session}/results/{participant_exam_result}/result', [ExamSessionController::class, 'downloadParticipantResult']);

        Route::get('/exam-sessions/{exam_session}/setting', [ExamSessionController::class, 'setting'])
            ->name('exam-sessions.setting');
        Route::post('/exam-sessions/{exam_session}/setting', [ExamSessionController::class, 'settingPost']);

        Route::get('/exam-sessions/{exam_session}/setting/{exam_session_setting}', [
            ExamSessionController::class, 'settingEdit',
        ])->name('exam-sessions.setting-edit');
        Route::put('/exam-sessions/{exam_session}/setting/{exam_session_setting}', [
            ExamSessionController::class, 'settingUpdate',
        ]);

        Route::post('/exam-sessions/{exam_session}/setting-questions', [ExamSessionController::class, 'settingQuestionsPost'])
            ->name('exam-sessions.setting-questions');
        Route::delete('/exam-sessions/{exam_session}/setting-questions/{exam_session_setting}', [ExamSessionController::class, 'settingDestroy'])
            ->name('exam-sessions.setting-destroy');

        Route::resource('log-logins', LogLoginController::class)
            ->only('index');

        Route::get('/app-setting', [
            AppSettingController::class, 'index',
        ])->name('app-settings.index');
        Route::put('/app-setting', [
            AppSettingController::class, 'update',
        ]);
        Route::get('/app-setting/destroy-image', [
            AppSettingController::class, 'appSettingImageDestroy',
        ])->name('app-settings.destroy-image');
    });

    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::put('/profiles', [ProfileController::class, 'update'])->name('profiles.update');
    Route::put('/profiles/password', [ProfileController::class, 'updatePassword'])
        ->name('profiles.update-password');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('guest')->group(function () {
    // auth
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->middleware('throttle:login');

    // auth
    Route::get('/registration', [AuthController::class, 'registration'])->name('auth.registration');
    Route::post('/registration', [AuthController::class, 'registrationPost']);
});
