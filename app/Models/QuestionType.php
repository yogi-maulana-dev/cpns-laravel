<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questionGroupType(): BelongsTo
    {
        return $this->belongsTo(QuestionGroupType::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
