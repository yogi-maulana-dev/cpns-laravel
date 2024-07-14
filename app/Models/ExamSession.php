<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExamSession extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    const PREFIX_CODE = 'EXM';

    public function scopeForMe(Builder $query)
    {
        return $query
            ->whereRelation(
                'participants',
                'participant_id',
                '=',
                auth()->user()->lazyLoadParticipant()->id
            );
    }

    public function scopeFinished(Builder $query)
    {
        return $query
            ->whereRelation(
                'participantExamResults',
                fn ($q) => $q
                    ->where('participant_id', auth()->user()->lazyLoadParticipant()->id)
                    ->whereNotNull('finished_at')
            );
    }

    public function scopeOpen(Builder $query)
    {
        return $query
            ->whereDate('start_at', '<=', now())
            ->whereDate('end_at', '>=', now());
    }

    public function isOpen()
    {
        return now()->between($this->start_at, $this->end_at);
    }

    public function isNotStartedYet()
    {
        return now()->isBefore($this->start_at);
    }

    public function isClosed()
    {
        return now()->isAfter($this->end_at);
    }

    public static function getNewCode(): string
    {
        return sprintf('%s-%s', self::PREFIX_CODE, strtoupper(Str::random(6)));
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function examSessionSettings(): HasMany
    {
        return $this->hasMany(ExamSessionSetting::class);
    }

    public function participantExamResults(): HasMany
    {
        return $this->hasMany(ParticipantExamResult::class);
    }

    public function participantAnswers(): HasMany
    {
        return $this->hasMany(ParticipantAnswer::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class);
    }
}
