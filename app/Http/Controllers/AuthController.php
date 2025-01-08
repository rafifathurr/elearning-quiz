<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\ResultDetail;
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

                if ($user->hasRole('user')) {
                    $result = Result::where('user_id', $user->id)
                        ->whereNull('finish_time')
                        ->first();

                    $currentDateTime = \Carbon\Carbon::now();
                    if ($result) {
                        $startTime = \Carbon\Carbon::parse($result->start_time);
                        $endTime = $startTime->copy()->addSeconds($result->time_duration);

                        // Cek jika waktu belum habis dan quiz belum selesai
                        if ($currentDateTime->lte($endTime)) {
                            $remainingSeconds = $endTime->timestamp - $currentDateTime->timestamp;
                            if ($result->quiz->type_aspect == 'kecermatan') {
                                return redirect()->route('kecermatan.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]);
                            } else {
                                return redirect()->route('admin.quiz.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]);
                            }
                        } else {
                            // Jika sudah selesai, update dan redirect ke halaman yang tepat
                            $total_score = ResultDetail::where('result_id', $result->id)->sum('score');
                            $result->update([
                                'finish_time' => $endTime,
                                'total_score' => $total_score
                            ]);
                            return redirect()->route('home')->with(['success' => 'Login Berhasil']);
                        }
                    } else {
                        // Jika result tidak ditemukan, redirect ke halaman lain
                        return redirect()->route('home')->with(['success' => 'Login Berhasil']);
                    }
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
