<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum ResultDisplayStatus: int
{
    use InvokableCases, Names, Values;

    case NOTHING = 0;
    case QUESTION_PARTICIPANT_ANSWER = 1;
    case QUESTION_PARTICIPANT_ANSWER_AND_DISCUSSION = 2;

    public static function getList()
    {
        return [
            self::NOTHING() => 'Jangan Tampilkan',
            self::QUESTION_PARTICIPANT_ANSWER() => 'Soal & Jawaban Peserta',
            self::QUESTION_PARTICIPANT_ANSWER_AND_DISCUSSION() => 'Soal, Jawaban Peserta & Pembahasan',
        ];
    }
}
