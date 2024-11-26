<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
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
            $email_or_username = $request->input('username');
            $field = filter_var($email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $request->merge([$field => $email_or_username]);

            // Validasi input
            $request->validate([
                'password' => 'required',
                $field => 'required',
            ]);

            // Mencari user berdasarkan email atau username, dengan kondisi deleted_at null
            $user = User::where($field, $email_or_username)
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
                    ->with(['failed' => 'Email atau Username Salah'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
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
