<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'title' => 'Users Management',
        ]);
    }

    public function trash()
    {
        return view('users.trash', [
            'title' => 'Sampah: Users Management',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
            'title' => 'Tambah User Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
            'role' => 'required|in:'.implode(',', [UserRole::SUPERADMIN(), UserRole::OPERATOR_UJIAN(), UserRole::OPERATOR_SOAL()]),
        ]);

        User::create(array_merge($validatedData, [
            'password' => Hash::make($validatedData['password']),
        ]));

        return redirect()->route('users.index')
            ->with('success', 'Data user baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'title' => 'Detail User',
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $userEmailUniqueValidation = $user->email === $request->email ? '' : '|unique:users,email';

        $rules = array_merge(
            [
                'name' => 'required|max:128',
                'email' => 'required|email'.$userEmailUniqueValidation,
            ],
            $user->isSuperadmin() ? ['role' => 'required|in:'.implode(',', [
                UserRole::SUPERADMIN(),
                UserRole::OPERATOR_UJIAN(),
                UserRole::OPERATOR_SOAL(),
            ])] : [],
            $request->input('password') ?
                ['password' => 'confirmed'] : []
        );

        $validatedData = $request->validate($rules);

        $user->update(array_merge($validatedData, array_key_exists('password', $validatedData) ? [
            'password' => Hash::make($validatedData['password']),
        ] : []));

        return redirect()->route('users.index')
            ->with('success', 'Data user berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    }
}
