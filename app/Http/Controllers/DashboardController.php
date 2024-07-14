<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Participant;
use App\Models\Question;
use App\Models\QuestionGroupType;
use App\Models\QuestionType;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $second = app()->isProduction() ? 60 * 1 : 1;
        $cardStats = cache()->driver()->remember('dashboard-card-stats-'.auth()->id(), $second, function () {
            if (auth()->user()->isSuperadmin()) {
                return $this->getCardStats();
            }

            if (auth()->user()->isOperatorSoal()) {
                return $this->getOperatorSoalCardStats();
            }

            if (auth()->user()->isOperatorUjian()) {
                return $this->getOperatorUjianCardStats();
            }

            if (auth()->user()->isParticipant()) {
                return $this->getParticipantCardStats();
            }

            return [];
        });

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'cardStats' => $cardStats,
        ]);
    }

    private function getCardStats(): array
    {
        return array_merge([
            [
                'name' => 'Data Dasar', 'items' => [
                    [
                        'label' => 'Data Tipe Kelompok Soal',
                        'prefix' => 'Tipe',
                        'count' => QuestionGroupType::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('question-group-types.index'),
                        'icon' => 'box',
                    ],
                    [
                        'label' => 'Data Tipe Soal',
                        'prefix' => 'Tipe',
                        'count' => QuestionType::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('question-types.index'),
                        'icon' => 'tag',
                    ],
                    [
                        'label' => 'Data Soal',
                        'prefix' => 'Soal',
                        'count' => Question::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                    [
                        'label' => 'Data Soal Hari ini',
                        'prefix' => 'Soal',
                        'count' => Question::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                    [
                        'label' => 'Data Soal Bulan ini',
                        'prefix' => 'Soal',
                        'count' => Question::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                ],
            ],
            [
                'name' => 'Data Utama Peserta', 'items' => [
                    [
                        'label' => 'Data Peserta',
                        'prefix' => 'Peserta',
                        'count' => Participant::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Hari ini',
                        'prefix' => 'Peserta',
                        'count' => Participant::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Bulan ini',
                        'prefix' => 'Peserta',
                        'count' => Participant::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Aktif',
                        'prefix' => 'Peserta',
                        'count' => Participant::active()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Non-Aktif',
                        'prefix' => 'Peserta',
                        'count' => Participant::nonActive()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Laki-Laki',
                        'prefix' => 'Peserta',
                        'count' => Participant::male()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Perempuan',
                        'prefix' => 'Peserta',
                        'count' => Participant::female()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                ],
            ],
            [
                'name' => 'Data Utama Sesi Ujian', 'items' => [
                    [
                        'label' => 'Data Sesi Ujian',
                        'prefix' => 'Sesi Ujian',
                        'count' => ExamSession::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('exam-sessions.index'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Hari ini',
                        'prefix' => 'Sesi Ujian',
                        'count' => ExamSession::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('exam-sessions.index'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Bulan ini',
                        'prefix' => 'Sesi Ujian',
                        'count' => ExamSession::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('exam-sessions.index'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Sedang Berlangsung',
                        'prefix' => 'Sesi Ujian',
                        'count' => ExamSession::open()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('exam-sessions.index'),
                        'icon' => 'file-text',
                    ],
                ],
            ],
            [
                'name' => 'Data Users', 'items' => [
                    [
                        'label' => 'Data Users',
                        'prefix' => 'Users',
                        'count' => User::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Hari ini',
                        'prefix' => 'Users',
                        'count' => User::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Bulan ini',
                        'prefix' => 'Users',
                        'count' => User::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Superadmin',
                        'prefix' => 'Users',
                        'count' => User::superadmin()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Operator Soal',
                        'prefix' => 'Users',
                        'count' => User::operatorSoal()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Operator Ujian',
                        'prefix' => 'Users',
                        'count' => User::onlyOperatorUjian()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Users Peserta',
                        'prefix' => 'Users',
                        'count' => User::peserta()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('users.index'),
                        'icon' => 'users',
                    ],
                ],
            ],
        ]);
    }

    private function getOperatorSoalCardStats(): array
    {
        return array_merge([
            [
                'name' => 'Data Dasar', 'items' => [
                    [
                        'label' => 'Data Tipe Kelompok Soal',
                        'prefix' => 'Tipe',
                        'count' => QuestionGroupType::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('question-group-types.index'),
                        'icon' => 'box',
                    ],
                    [
                        'label' => 'Data Tipe Soal',
                        'prefix' => 'Tipe',
                        'count' => QuestionType::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('question-types.index'),
                        'icon' => 'tag',
                    ],
                    [
                        'label' => 'Data Soal',
                        'prefix' => 'Soal',
                        'count' => Question::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                    [
                        'label' => 'Data Soal Hari ini',
                        'prefix' => 'Soal',
                        'count' => Question::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                    [
                        'label' => 'Data Soal Bulan ini',
                        'prefix' => 'Soal',
                        'count' => Question::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('questions.index'),
                        'icon' => 'help-circle',
                    ],
                ],
            ],
        ]);
    }

    private function getOperatorUjianCardStats(): array
    {
        return array_merge([
            [
                'name' => 'Data Utama Peserta', 'items' => [
                    [
                        'label' => 'Data Peserta',
                        'prefix' => 'Peserta',
                        'count' => Participant::count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Hari ini',
                        'prefix' => 'Peserta',
                        'count' => Participant::whereDate('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Bulan ini',
                        'prefix' => 'Peserta',
                        'count' => Participant::whereMonth('created_at', today())->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Aktif',
                        'prefix' => 'Peserta',
                        'count' => Participant::active()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Non-Aktif',
                        'prefix' => 'Peserta',
                        'count' => Participant::nonActive()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Laki-Laki',
                        'prefix' => 'Peserta',
                        'count' => Participant::male()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Data Peserta Perempuan',
                        'prefix' => 'Peserta',
                        'count' => Participant::female()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('participants.index'),
                        'icon' => 'users',
                    ],
                ],
            ],
        ]);
    }

    private function getParticipantCardStats(): array
    {
        $examSessionIds = ExamSession::forMe()->finished()->pluck('id');

        return array_merge([
            [
                'name' => 'Data Dasar', 'items' => [
                    [
                        'label' => 'Data Sesi Ujian Saya',
                        'prefix' => 'Sesi',
                        'count' => ExamSession::forMe()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('me.exam-sessions.list'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Sedang Berlangsung',
                        'prefix' => 'Sesi',
                        'count' => ExamSession::forMe()->open()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('me.exam-sessions.list'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Sudah Selesai',
                        'prefix' => 'Sesi',
                        'count' => ExamSession::forMe()->finished()->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('me.exam-sessions.histories'),
                        'icon' => 'file-text',
                    ],
                    [
                        'label' => 'Data Sesi Ujian Belum Selesai',
                        'prefix' => 'Sesi',
                        'count' => ExamSession::forMe()->whereNotIn('id', $examSessionIds)->count(),
                        'type_color' => 'primary',
                        'more_info_link' => route('me.exam-sessions.list'),
                        'icon' => 'file-text',
                    ],
                ],
            ],
        ]);
    }
}
