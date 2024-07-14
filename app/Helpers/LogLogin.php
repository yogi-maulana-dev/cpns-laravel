<?php

namespace App\Helpers;

use App\Models\LogLogin as ModelsLogLogin;

class LogLogin
{
    public static function addToLog($subject)
    {
        ModelsLogLogin::create([
            'subject' => $subject,
            'ip' => request()->ip(),
            'agent' => request()->header('user-agent'),
            'user_id' => auth()->check() ? auth()->user()->id : null,
        ]);
    }

    public static function logActivityLists()
    {
        return ModelsLogLogin::latest()->get();
    }
}
