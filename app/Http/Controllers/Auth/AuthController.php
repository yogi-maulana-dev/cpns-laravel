<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Helpers\LogLogin;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequests\LoginRequest;
use App\Http\Requests\ParticipantRequest;
use App\Models\Participant;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Masuk',
        ]);
    }

    public function authenticate(LoginRequest $request)
    {
        $remember = $request->boolean('remember');
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials, $remember)) { // login gagal
            request()->session()->regenerate();
            $intendedUrl = request('to') ?? route('dashboard.index');

            $data = [
                'success' => true,
                'redirect_to' => $intendedUrl,
                'message' => 'Login berhasil, silahkan tunggu...',
            ];

            LogLogin::addToLog(
                sprintf('USER DENGAN EMAIL %s BERHASIL MASUK.', $credentials['email'])
            );

            return response()->json($data);
        }

        $data = [
            'success' => false,
            'message' => 'Login gagal, silahkan coba lagi!',
        ];

        LogLogin::addToLog(sprintf('USER GAGAL LOGIN DENGAN MEMASUKAN EMAIL %s.', $credentials['email']));

        return response()->json($data)->setStatusCode(400);
    }

    public function logout()
    {
        $id = auth()->id();
        auth()->logout();

        request()->session()->regenerate();
        request()->session()->regenerateToken();

        LogLogin::addToLog(
            sprintf('USER BERHASIL LOGOUT (KELUAR) DENGAN ID #%s.', $id)
        );

        return redirect()->route('auth.login')->with('success', 'Anda berhasil keluar.');
    }

    public function registration()
    {
        return view('auth.registration', [
            'title' => 'Pendaftaran Peserta Ujian',
        ]);
    }

    public function registrationPost(ParticipantRequest $request)
    {
        $validatedDataPassword = $this->validate($request, [
            'password' => 'required|min:5|confirmed',
        ], attributes: [
            'password' => 'Password',
        ]);

        try {
            DB::beginTransaction();
            $participant = Participant::create($request->validated());
            $user = User::create([
                'name' => $participant->name,
                // 'email' => $participant->nik.'@gmail.com',
                'email' => $participant->email,
                'password' => Hash::make($validatedDataPassword['password']),
                'role' => UserRole::PARTICIPANT(),
            ]);

            $participant->update([
                'user_id' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('auth.login')->with('success', 'Akun peserta ujian anda berhasil didaftarkan, silahkan masuk!');
        } catch (Exception $ex) {
            DB::rollBack();

            return redirect()->route('auth.registration')->with('failed', 'Ada masalah pada server.');
        }
    }
}
