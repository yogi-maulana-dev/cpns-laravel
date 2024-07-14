<?php

namespace App\Http\Controllers;

use App\Helpers\LogLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profiles.index', [
            'title' => 'Pengaturan Profile',
            'user' => auth()->user(),
            'participant' => auth()->user()->isParticipant() ? auth()->user()->lazyLoadParticipant() : null,
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $lastName = auth()->user()->name;

        auth()->user()->update([
            'name' => $validatedData['name'],
        ]);

        LogLogin::addToLog(sprintf(
            '%s: MENGUBAH PROFILE NYA DARI NAMA `%s` MENJADI `%s`.',
            auth()->user()->email,
            $lastName,
            $validatedData['name']
        ));

        return redirect()->route('profiles.index')->with('success', 'Profile berhasil diubah.');
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|current_password',
            'new_password' => 'required|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validatedData['new_password']),
            'password_was_changed' => true,
        ]);

        LogLogin::addToLog(sprintf('%s (%s) BERHASIL MELAKUKAN GANTI PASSWORD', auth()->user()->email, auth()->id()));

        auth()->logout();
        request()->session()->regenerate();
        request()->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', 'Password berhasil diubah, silahkan login ulang!');
    }
}
