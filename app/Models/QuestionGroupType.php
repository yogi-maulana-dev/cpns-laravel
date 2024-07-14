<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionGroupType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questionTypes(): HasMany
    {
        return $this->hasMany(QuestionType::class);
    }
}
