<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Cek apakah user sudah ada di database
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Jika user sudah ada, langsung login
            $user->update([
                'deleted_at' => null
            ]);
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
            // Jika belum terdaftar, arahkan ke halaman register
            return redirect()->route('auth.create')->with([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
            ]);
        }
    }

    public function create()
    {
        return view('auth.registerGoogle')->with([
            'name' => session('name'),
            'email' => session('email'),
            'google_id' => session('google_id'),
        ]);
    }

    public function storeDataGoogle(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'google_id' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => strtoupper($request->name),
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt(Str::random(16)),
                'google_id' => $request->google_id,
            ]);

            $user->assignRole('user');

            Auth::login($user);
            return redirect('/home')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }


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
                            OrderDetail::where('id', $result->order_detail_id)->update([
                                'updated_at' => now()
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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Mengirimkan link reset password ke email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        $token = Str::random(60); // Token acak

        // Simpan token ke database dengan updateOrInsert
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token), // Simpan dalam bentuk hash
                'created_at' => now()
            ]
        );

        // Kirim email reset password
        Mail::send('auth.mail.forgot-password', ['token' => $token, 'email' => $email], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    // Menampilkan halaman reset password
    public function showResetForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    // Memproses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        // Cari token di database
        $reset = DB::table('password_reset_tokens')->where('token', Hash::make($request->token))->first();

        if (!$reset) {
            return back()->withErrors(['token' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // Update password user
        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token reset setelah digunakan
        DB::table('password_reset_tokens')->where('email', $reset->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset, silakan login.');
    }
}
