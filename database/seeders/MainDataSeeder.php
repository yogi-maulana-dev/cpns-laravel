<?php

namespace Database\Seeders;

use App\Enums\OrderOfQuestion;
use App\Enums\ResultDisplayStatus;
use App\Enums\UserRole;
use App\Models\Answer;
use App\Models\ExamSession;
use App\Models\Participant;
use App\Models\Question;
use App\Models\QuestionGroupType;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MainDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            'participants', 'question_types', 'question_group_types', 'questions', 'answers', 'exam_sessions', 'exam_session_settings', 'exam_session_question', 'exam_session_participant',
        ];

        foreach ($tables as $table) {
            Schema::disableForeignKeyConstraints();
            DB::table($table)->truncate();
            Schema::enableForeignKeyConstraints();
        }

        Participant::factory(10)->create();

        // test participant user
        $participant = Participant::factory(1)->create([
            'name' => 'Peserta',
            'nik' => '1234567890123456',
        ])->first();
        $user = $participant->user()->create([
            'email' => $participant->nik.'@gmail.com',
            'name' => $participant->name,
            'password' => Hash::make('password'),
            'role' => UserRole::PARTICIPANT(),
        ]);
        $participant->update([
            'user_id' => $user->id,
        ]);
        // end

        $idStart = 1;
        foreach (['TKP', 'TWK', 'TIU'] as $type) {
            $questionGroupType = QuestionGroupType::create([
                'id' => 100 + $idStart,
                'name' => $type,
            ]);

            $idStart += 1;
            for ($i = 1; $i < 2; $i++) {
                QuestionType::create([
                    'name' => sprintf('%s %s', $questionGroupType->name, $i),
                    'question_group_type_id' => $questionGroupType->id,
                ]);
            }
        }

        Question::factory(100)->create();
        $questions = Question::with('questionType')->get();

        foreach ($questions as $question) {
            for ($i = 1; $i <= 5; $i++) {
                Answer::create([
                    'answer_text' => fake()->text(),
                    'order_index' => $i,
                    'weight_score' => fake()->numberBetween(2, 15),
                    'question_id' => $question->id,
                ]);
            }
        }

        $questionGroupTypes = QuestionGroupType::all();

        for ($i = 1; $i <= 15; $i++) {
            $examSession = ExamSession::create([
                'code' => ExamSession::getNewCode(),
                'name' => 'Sesi Ujian #'.$i,
                'description' => fake()->text(),
                'time' => fake()->numberBetween(60, 180),
                'start_at' => fake()->dateTimeBetween('-1 months'),
                'end_at' => fake()->dateTimeBetween('-1 months', '2 months'),
                'created_by' => 1,
                'result_display_status' => fake()->randomElement(ResultDisplayStatus::values()),
                'order_of_question' => fake()->randomElement(OrderOfQuestion::values()),
            ]);

            $noq = 3;

            foreach ($questionGroupTypes as $questionGroupType) {
                $examSession->examSessionSettings()->create([
                    'question_group_type_id' => $questionGroupType->id,
                    'number_of_question' => $noq,
                    'passing_grade' => 10,
                ]);

                $examSession->questions()->attach(
                    $questions
                        ->where('questionType.question_group_type_id', '=', $questionGroupType->id)
                        ->random($noq)
                        ->pluck('id')
                );
            }

            $participant->examSessions()->attach($examSession);
        }
    }
}
