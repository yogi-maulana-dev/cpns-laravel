<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamScoreQGT extends Model
{
    use HasFactory;

    protected $table = 'exam_score_qgts';

    protected $guarded = ['id'];

    public function participantExamResult(): BelongsTo
    {
        return $this->belongsTo(ParticipantExamResult::class);
    }

    public function questionGroupType(): BelongsTo
    {
        return $this->belongsTo(QuestionGroupType::class);
    }
}
