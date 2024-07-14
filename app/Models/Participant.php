<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function getGenderText(): string
    {
        return $this->gender ? 'Perempuan' : 'Laki-Laki';
    }

    public function isMale(): string
    {
        return ! $this->gender; // false === "male"
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeNonActive($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeMale($query)
    {
        return $query->where('gender', false);
    }

    public function scopeFemale($query)
    {
        return $query->where('gender', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function examSessions(): BelongsToMany
    {
        return $this->belongsToMany(ExamSession::class);
    }
}
