<?php

namespace App\Http\Controllers;

use App\Mail\FinishMail;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\Result;
use App\Models\TypePackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        if (User::find(Auth::user()->id)->hasRole('user')) {
            $resultData = Result::where('user_id', Auth::user()->id)->whereNotNull('finish_time')
                ->whereNotNull('order_detail_id')->where('status', 0)->first();

            if ($resultData) {
                $data = [
                    'result' => $resultData,
                    'name' => Auth::user()->name,
                ];

                // Selain Kecermatan
                if ($resultData->quiz->type_aspect != 'kecermatan') {
                    $questionsPerAspect = $resultData->details
                        ->groupBy('aspect_id')
                        ->map(function ($details, $aspectId) {
                            $totalQuestions = $details->count();
                            $correctQuestions = $details->where('score', 1)->count();
                            $percentage = $totalQuestions > 0
                                ? ($correctQuestions / $totalQuestions) * 100
                                : 0;

                            return [
                                'aspect_name' => $details->first()->aspect->name ?? 'Unknown Aspect',
                                'total_questions' => $totalQuestions,
                                'correct_questions' => $correctQuestions,
                                'percentage' => round($percentage, 2),
                            ];
                        });

                    $questionsPerAspect = $questionsPerAspect->sortByDesc('percentage');

                    Log::info('Generate PDF');
                    // Pemanggilan PDF yang benar
                    // Generate PDF dari hasil
                    $pdf = app('dompdf.wrapper')->loadView('result_pdf', compact('resultData', 'questionsPerAspect'));
                }

                // Kecermatan
                else {
                    $correctAnswers = $resultData->details->where('score', 1)->count();
                    $totalQuestions = $resultData->details->count();
                    $wrongAnswers = $totalQuestions - $correctAnswers;

                    // Kecepatan
                    $speed = '';
                    if ($correctAnswers > 300) {
                        $speed = 'B'; // Baik
                    } elseif ($correctAnswers >= 280 && $correctAnswers < 300) {
                        $speed = 'CB'; // Cukup Baik
                    } elseif ($correctAnswers >= 260 && $correctAnswers < 280) {
                        $speed = 'C'; // Cukup
                    } elseif ($correctAnswers >= 240 && $correctAnswers < 260) {
                        $speed = 'K'; // Kurang
                    } elseif ($correctAnswers >= 0 && $correctAnswers < 240) {
                        $speed = 'KS'; // Kurang Sekali
                    }

                    // Ketelitian
                    $accuracy = ($wrongAnswers / $totalQuestions) * 100;
                    $accuracyLabel = '';
                    if ($accuracy < 4) {
                        $accuracyLabel = 'B'; // Baik
                    } elseif ($accuracy >= 4.1 && $accuracy < 6) {
                        $accuracyLabel = 'CB'; // Cukup Baik
                    } elseif ($accuracy >= 6.1 && $accuracy < 8) {
                        $accuracyLabel = 'C'; // Cukup
                    } elseif ($accuracy >= 8.1 && $accuracy < 10) {
                        $accuracyLabel = 'K'; // Kurang
                    } elseif ($accuracy >= 10.1) {
                        $accuracyLabel = 'KS'; // Kurang Sekali
                    }

                    Log::info('Generate PDF');
                    Log::info('Speed: ' . $speed);
                    Log::info('Accuracy Label: ' . $accuracyLabel);

                    // Pemanggilan PDF yang benar
                    // Generate PDF dari hasil
                    $chartPath = storage_path('app/public/kecermatan/chart_' . Auth::user()->id . '.png');

                    $pdf = app('dompdf.wrapper')->loadView('result_pdf', compact('resultData', 'speed', 'accuracyLabel', 'chartPath'));
                }

                // Dapatkan nama quiz dan nama peserta
                $quizName = str_replace(' ', '_', strtolower($resultData->quiz->name)); // Ganti spasi dengan underscore
                $participantName = str_replace(' ', '_', strtolower(Auth::user()->name)); // Ganti spasi dengan underscore

                // Buat nama file yang unik
                $pdfFileName = "{$quizName}_{$participantName}.pdf";

                // Path penyimpanan PDF
                $pdfPath = storage_path("app/public/{$pdfFileName}");

                // Simpan PDF ke path yang telah dibuat
                $pdf->save($pdfPath);


                // Pastikan file PDF ada
                if (!file_exists($pdfPath)) {
                    Log::error('PDF tidak ditemukan di path: ' . $pdfPath);
                } else {
                    Log::info('PDF berhasil dibuat: ' . $pdfPath);

                    // Kirim email dengan lampiran PDF
                    Log::info('Email dimasukkan ke antrian');
                    $sendMail = Mail::to(Auth::user()->email)->send(new FinishMail($data, $pdfPath));
                    Log::info('Email berhasil dikirim ke antrian');

                    if ($sendMail) {
                        $resultData->update([
                            'status' => 1
                        ]);

                        if (file_exists($pdfPath)) {
                            unlink($pdfPath);
                            Log::info("PDF berhasil dihapus: {$pdfPath}");
                        }
                    }
                }
            };
        }


        $typePackages = TypePackage::where('id_parent', 0)
            ->whereNull('deleted_at')
            ->with('children', 'package')
            ->orderBy('created_at', 'DESC')
            ->get();
        $tryOutPackages = $typePackages->filter(function ($item) {
            return strtoupper($item->name) === 'TRY OUT';
        });


        $otherPackages = $typePackages->reject(function ($item) {
            return strtoupper($item->name) === 'TRY OUT';
        });

        $data = [
            'tryOutPackages' => $tryOutPackages,
            'otherPackages'  => $otherPackages,
        ];
        $data['packages'] = Package::whereNull('deleted_at')
            ->where('status', 1)
            ->whereHas('voucher')
            ->get();

        return view('home', $data);
    }

    public function saveChart(Request $request)
    {
        $data = $request->chartImage;

        // Hapus header data:image/png;base64,
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ', '+', $data);

        $fileName = 'chart_' . Auth::user()->id . '.png';
        $filePath = storage_path('app/public/kecermatan/' . $fileName);

        file_put_contents($filePath, base64_decode($data));

        return response()->json(['success' => true]);
    }


    public function landingPage()
    {
        if (Auth::check()) {
            if (User::find(Auth::user()->id)->hasRole('user')) {
                $resultData = Result::where('user_id', Auth::id())
                    ->whereNull('finish_time')
                    ->first();
                if ($resultData) {
                    Auth::logout();
                    return redirect()->route('login');
                }
            }
        }

        $typePackages = TypePackage::where('id_parent', 0)
            ->whereNull('deleted_at')
            ->with('children', 'package')
            ->orderBy('created_at', 'DESC')
            ->get();
        $tryOutPackages = $typePackages->filter(function ($item) {
            return strtoupper($item->name) === 'TRY OUT';
        });


        $otherPackages = $typePackages->reject(function ($item) {
            return strtoupper($item->name) === 'TRY OUT';
        });

        $data = [
            'tryOutPackages' => $tryOutPackages,
            'otherPackages'  => $otherPackages,
        ];

        return view('landingPage', $data);
    }

    public function contact()
    {
        return view('contact');
    }
}
