<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::updated(function ($appSetting) {
            Cache::forget('appSetting');
        });
    }

    protected $guarded = ['id'];
}
