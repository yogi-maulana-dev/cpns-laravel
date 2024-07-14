<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSessionSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questionGroupType(): BelongsTo
    {
        return $this->belongsTo(QuestionGroupType::class);
    }
}
