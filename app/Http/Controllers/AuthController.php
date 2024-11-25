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

    public function showVerifyForm(Request $request)
    {
        return view('auth.verify', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with(['failed' => 'Email tidak ditemukan.']);
        }

        if ($user->otp === $request->otp && $user->otp_expired_at->isFuture()) {
            $user->otp = null; // Reset OTP setelah berhasil
            $user->otp_expired_at = null;
            $user->save();

            return redirect()->route('login')->with(['success' => 'Verifikasi berhasil.']);
        } else {
            return redirect()->back()->with(['failed' => 'OTP tidak valid atau telah kedaluwarsa.']);
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingPage')->with(['success' => 'Logout Berhasil']);
    }
}
