<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }



    public function authenticate(Request $request)
    {
        try {
            // Memeriksa apakah input adalah email atau username
            $email_or_username = $request->input('username');
            $fields = filter_var($email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Validasi input
            $request->validate([
                'password' => 'required',
                $fields => 'required',
            ]);

            // Mencari user berdasarkan email atau username, dengan kondisi deleted_at null
            $user = User::where($fields, $email_or_username)
                ->whereNull('deleted_at') // Pastikan pengguna belum dihapus
                ->first();

            // Verifikasi password secara manual
            if ($user && Hash::check($request->password, $user->password)) {
                // Login pengguna secara manual
                Auth::login($user);

                // Redirect berdasarkan peran pengguna
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.quiz.index')->with(['success' => 'Login Berhasil']);
                } else {
                    return redirect()->route('home')->with(['success' => 'Login Berhasil']);
                }
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['username' => 'These credentials do not match our records.'])
                    ->withInput();
            }
        } catch (\Throwable $th) {
            // Menangani kesalahan dengan memberikan pesan yang sesuai
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again later.'])->withInput();
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingPage')->with(['success' => 'Logout Berhasil']);
    }
}
