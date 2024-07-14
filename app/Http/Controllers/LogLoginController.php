<?php

namespace App\Http\Controllers;

class LogLoginController extends Controller
{
    public function index()
    {
        return view('log-logins.index', [
            'title' => 'Data Log Login',
        ]);
    }
}
