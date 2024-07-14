<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParticipantExamResult extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime', 'end_at' => 'datetime'];

    public function examScoreQGTs(): HasMany
    {
        return $this->hasMany(ExamScoreQGT::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }
}
