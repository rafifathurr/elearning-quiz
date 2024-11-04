<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Mencari user berdasarkan email atau username
            $credentials = User::where($fields, $email_or_username)->first();

            // Memeriksa apakah kredensial valid
            if ($credentials && Auth::attempt([$fields => $email_or_username, 'password' => $request->password])) {
                // Redirect berdasarkan peran pengguna
                if (User::find(auth()->user()->id)->hasRole('admin')) {
                    return redirect()->route('admin.quiz.index');
                } else {
                    return view('home');
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
        return redirect()->route('login');
    }
}
