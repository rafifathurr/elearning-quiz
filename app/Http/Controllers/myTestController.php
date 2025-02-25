<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassCounselor;
use App\Models\ClassPackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class myTestController extends Controller
{
    public function index()
    {
        $datatable_route = route('mytest.dataTable');
        return view('mytest.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');
        $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNull('class')
                    ->orWhere('class', 0);
            })
            ->pluck('package_id');

        $myTest = OrderDetail::whereIn('order_id', $orderIds)
            ->whereIn('package_id', $orderPackageIds)
            ->whereNull('deleted_at')
            ->whereNotNull('quiz_id')
            ->get();

        return DataTables::of($myTest)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return $data->order->user->name;
            })
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('quiz', function ($data) {
                return $data->quiz->name;
            })
            ->addColumn('type_quiz', function ($data) {
                return $data->quiz->type_aspect;
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';

                $result = Result::where('quiz_id', $data->quiz->id)
                    ->where('user_id', Auth::user()->id)
                    ->where('order_detail_id', $data->id)
                    ->whereNull('finish_time')
                    ->first();

                $review = Result::where('quiz_id', $data->quiz->id)
                    ->where('user_id', Auth::user()->id)
                    ->where('order_detail_id', $data->id)
                    ->whereNotNull('finish_time')
                    ->first();

                if ($result) {
                    $currentDateTime = \Carbon\Carbon::now();
                    $startTime = \Carbon\Carbon::parse($result->start_time);
                    $endTime = $startTime->copy()->addSeconds($result->time_duration);

                    if ($currentDateTime->lte($endTime)) {
                        // Hitung sisa durasi secara langsung
                        $remainingSeconds = $endTime->timestamp - $currentDateTime->timestamp;
                        if ($data->quiz->type_aspect == 'kecermatan') {
                            $btn_action .= '<a href="' . route('kecermatan.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) . '" class="btn btn-sm btn-warning btn-lanjutkan">Lanjutkan</a>';
                        } else {
                            $btn_action .= '<a href="' . route('admin.quiz.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) . '" class="btn btn-sm btn-warning btn-lanjutkan">Lanjutkan</a>';
                        }
                    } else {
                        // Update finish_time jika waktu habis
                        $total_score = ResultDetail::where('result_id', $result->id)->sum('score');
                        $result->update([
                            'finish_time' => $endTime,
                            'total_score' => $total_score
                        ]);

                        // Setelah waktu habis, langsung tampilkan tombol Review
                        if ($data->quiz->type_aspect == 'kecermatan') {
                            $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $result->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                        } else {
                            $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $result->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                        }
                    }
                } elseif ($review) {
                    if ($data->quiz->type_aspect == 'kecermatan') {
                        $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                    } else {
                        $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                    }
                } else {
                    $onGoingTest = Result::where('user_id', Auth::user()->id)
                        ->whereNull('finish_time')
                        ->first();

                    // Jika ada data tes lama tanpa finish_time, selesaikan secara otomatis
                    if ($onGoingTest && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($onGoingTest->start_time)->copy()->addSeconds($onGoingTest->time_duration))) {
                        $total_score = ResultDetail::where('result_id', $onGoingTest->id)->sum('score');
                        $onGoingTest->update([
                            'finish_time' => \Carbon\Carbon::now(),
                            'total_score' => $total_score,
                        ]);
                        $onGoingTest = null; // Hapus status tes berjalan
                    }

                    if (!$onGoingTest) {
                        $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                    }
                }
                $btn_action .= '</div>';
                return $btn_action;
            })


            ->only(['name', 'package', 'quiz', 'type_quiz', 'action'])
            ->rawColumns(['action'])
            ->make(true);
    }

    public function history()
    {
        $datatable_route = route('mytest.dataTableHistory');
        return view('mytest.history', compact('datatable_route'));
    }

    public function dataTableHistory()
    {
        if (User::find(Auth::user()->id)->hasRole('counselor') && !User::find(Auth::user()->id)->hasRole('admin') && !User::find(Auth::user()->id)->hasRole('manager')) {
            $classIds = ClassCounselor::where('counselor_id', Auth::user()->id)->pluck('class_id');
            $orderPackageIds = ClassAttendance::whereIn('class_id', $classIds)->pluck('order_package_id');
            $packageIds = OrderPackage::whereIn('id', $orderPackageIds)->pluck('package_id');
            $orderIds = OrderPackage::whereIn('id', $orderPackageIds)->pluck('order_id');
            $orderDetailIds = Result::whereNotNull('finish_time')->pluck('order_detail_id');
            $myTest = OrderDetail::whereIn('id', $orderDetailIds)
                ->whereIn('order_id', $orderIds)
                ->whereIn('package_id', $packageIds)
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } elseif (User::find(Auth::user()->id)->hasAllRoles(['counselor', 'admin', 'manager'])) {
            $orderIds = Order::whereNull('deleted_at')
                ->where('status', 100)
                ->pluck('id');

            $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
                ->whereNull('deleted_at')
                ->pluck('package_id');

            $orderDetailIds = Result::whereNotNull('finish_time')
                ->pluck('order_detail_id');

            $myTest = OrderDetail::whereIn('id', $orderDetailIds)
                ->whereIn('order_id', $orderIds)
                ->whereIn('package_id', $orderPackageIds)
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } else {
            $orderIds = Order::whereNull('deleted_at')
                ->where('status', 100)
                ->pluck('id');

            $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
                ->whereNull('deleted_at')
                ->pluck('package_id');

            $orderDetailIds = Result::whereNotNull('finish_time')
                ->pluck('order_detail_id');

            $myTest = OrderDetail::whereIn('id', $orderDetailIds)
                ->whereIn('order_id', $orderIds)
                ->whereIn('package_id', $orderPackageIds)
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')
                ->get();
        }


        return DataTables::of($myTest)
            ->addIndexColumn()
            ->addColumn('updated_at', function ($data) {
                return \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y H:i');;
            })
            ->addColumn('name', function ($data) {
                return $data->order->user->name;
            })
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('quiz', function ($data) {
                return $data->quiz->name;
            })
            ->addColumn('type_quiz', function ($data) {
                return $data->quiz->type_aspect;
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $review = Result::where('quiz_id', $data->quiz->id)
                    ->where('order_detail_id', $data->id)
                    ->whereNotNull('finish_time')
                    ->first();
                if ($data->quiz->type_aspect == 'kecermatan') {
                    $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                } else {
                    $btn_action .= '<a href="' . route('mytest.review', ['id' => encrypt($review->id)]) . '" class="btn btn-sm btn-primary">Review</a>';
                }

                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete">Hapus</button>';

                $btn_action .= '</div>';
                return $btn_action;
            })


            ->only(['name', 'package', 'quiz', 'type_quiz', 'action', 'updated_at'])
            ->rawColumns(['action', 'updated_at'])
            ->make(true);
    }

    function review(string $id)
    {
        $decryptId = decrypt($id);
        $review = Result::where('id', $decryptId)
            ->with(['quiz', 'details.aspect'])
            ->firstOrFail();

        // Hitung data per aspek
        $questionsPerAspect = $review->details
            ->groupBy('aspect_id')
            ->map(function ($details) {
                $totalQuestions = $details->count();
                $correctQuestions = $details->where('score', 1)->count();
                $percentage = $totalQuestions > 0
                    ? ($correctQuestions / $totalQuestions) * 100
                    : 0;

                return [
                    'aspect_name' => $details->first()->aspect->name ?? 'Unknown Aspect',
                    'total_questions' => $totalQuestions,
                    'correct_questions' => $correctQuestions,
                    'percentage' => round($percentage, 2), // Dibulatkan 2 desimal
                ];
            });

        // Urutkan berdasarkan persentase tertinggi
        $questionsPerAspect = $questionsPerAspect->sortByDesc('percentage');
        return view('mytest.review', compact('review', 'questionsPerAspect'));
    }

    function destroy(string $id)
    {
        try {
            // Mulai transaksi
            DB::beginTransaction();

            // Cari data Result berdasarkan ID
            $result = Result::where('order_detail_id', $id)->first();

            if ($result) {
                // Hapus data ResultDetail terkait
                $detailDelete = ResultDetail::where('result_id', $result->id)->delete();

                // Pastikan detail berhasil dihapus
                if ($detailDelete !== false) {
                    // Hapus data Result
                    $resultDelete = $result->delete();

                    // Pastikan Result berhasil dihapus
                    if ($resultDelete) {
                        DB::commit(); // Commit transaksi jika berhasil
                        session()->flash('success', 'Riwayat test berhasil dihapus.');
                    } else {
                        DB::rollBack(); // Rollback jika gagal
                        session()->flash('failed', 'Gagal menghapus data riwayat test.');
                    }
                } else {
                    DB::rollBack(); // Rollback jika gagal menghapus detail
                    session()->flash('failed', 'Gagal menghapus data riwayat test.');
                }
            } else {
                session()->flash('failed', 'Data riwayat tidak ditemukan.');
            }
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi exception
            DB::rollBack();
            session()->flash('failed', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Datatable admin dan user gabung
    // public function dataTable()
    // {
    //     if (User::find(Auth::user()->id)->hasRole('user')) {
    //         $orderIds = Order::where('user_id', Auth::user()->id)
    //             ->whereNull('deleted_at')
    //             ->where('status', 100)
    //             ->pluck('id');
    //         $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
    //             ->whereNull('deleted_at')
    //             ->where(function ($query) {
    //                 $query->whereNull('class')
    //                     ->orWhere('class', 0);
    //             })
    //             ->pluck('package_id');

    //         $myTest = OrderDetail::whereIn('order_id', $orderIds)
    //             ->whereIn('package_id', $orderPackageIds)
    //             ->whereNull('deleted_at')
    //             ->whereNotNull('quiz_id')
    //             ->get();
    //     } elseif (User::find(Auth::user()->id)->hasRole('counselor')) {
    //         $classIds = ClassPackage::where('user_id', Auth::user()->id)->pluck('id');
    //         $orderPackageIds = ClassAttendance::whereIn('class_id', $classIds)->pluck('order_package_id');
    //         $packageIds = OrderPackage::whereIn('id', $orderPackageIds)->pluck('package_id');
    //         $orderIds = OrderPackage::whereIn('id', $orderPackageIds)->pluck('order_id');
    //         $orderDetailIds = Result::whereNotNull('finish_time')->pluck('order_detail_id');
    //         $myTest = OrderDetail::whereIn('id', $orderDetailIds)
    //             ->whereIn('order_id', $orderIds)
    //             ->whereIn('package_id', $packageIds)
    //             ->whereNull('deleted_at')
    //             ->get();
    //     } else {
    //         $orderIds = Order::whereNull('deleted_at')
    //             ->where('status', 100)
    //             ->pluck('id');

    //         $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
    //             ->whereNull('deleted_at')
    //             ->pluck('package_id');

    //         $orderDetailIds = Result::whereNotNull('finish_time')
    //             ->pluck('order_detail_id');

    //         $myTest = OrderDetail::whereIn('id', $orderDetailIds)
    //             ->whereIn('order_id', $orderIds)
    //             ->whereIn('package_id', $orderPackageIds)
    //             ->whereNull('deleted_at')
    //             ->get();
    //     }


    //     return DataTables::of($myTest)
    //         ->addIndexColumn()
    //         ->addColumn('name', function ($data) {
    //             return $data->order->user->name;
    //         })
    //         ->addColumn('package', function ($data) {
    //             return $data->package->name;
    //         })
    //         ->addColumn('quiz', function ($data) {
    //             return $data->quiz->name;
    //         })
    //         ->addColumn('type_quiz', function ($data) {
    //             return $data->quiz->type_aspect;
    //         })

    //         ->addColumn('action', function ($data) {
    //             $btn_action = '<div align="center">';

    //             if (User::find(Auth::user()->id)->hasRole('user')) {
    //                 $result = Result::where('quiz_id', $data->quiz->id)
    //                     ->where('user_id', Auth::user()->id)
    //                     ->where('order_detail_id', $data->id)
    //                     ->whereNull('finish_time')
    //                     ->first();

    //                 $review = Result::where('quiz_id', $data->quiz->id)
    //                     ->where('user_id', Auth::user()->id)
    //                     ->where('order_detail_id', $data->id)
    //                     ->whereNotNull('finish_time')
    //                     ->first();

    //                 if ($result) {
    //                     $currentDateTime = \Carbon\Carbon::now();
    //                     $startTime = \Carbon\Carbon::parse($result->start_time);
    //                     $endTime = $startTime->copy()->addSeconds($result->time_duration);

    //                     if ($currentDateTime->lte($endTime)) {
    //                         // Hitung sisa durasi secara langsung
    //                         $remainingSeconds = $endTime->timestamp - $currentDateTime->timestamp;
    //                         if ($data->quiz->type_aspect == 'kecermatan') {
    //                             $btn_action .= '<a href="' . route('kecermatan.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) . '" class="btn btn-sm btn-warning btn-lanjutkan">Lanjutkan</a>';
    //                         } else {
    //                             $btn_action .= '<a href="' . route('admin.quiz.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) . '" class="btn btn-sm btn-warning btn-lanjutkan">Lanjutkan</a>';
    //                         }
    //                     } else {
    //                         // Update finish_time jika waktu habis
    //                         $total_score = ResultDetail::where('result_id', $result->id)->sum('score');
    //                         $result->update([
    //                             'finish_time' => $endTime,
    //                             'total_score' => $total_score
    //                         ]);

    //                         // Setelah waktu habis, langsung tampilkan tombol Review
    //                         if ($data->quiz->type_aspect == 'kecermatan') {
    //                             $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $result->id]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                         } else {
    //                             $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $result->id]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                         }
    //                     }
    //                 } elseif ($review) {
    //                     if ($data->quiz->type_aspect == 'kecermatan') {
    //                         $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                     } else {
    //                         $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                     }
    //                 } else {
    //                     $onGoingTest = Result::where('user_id', Auth::user()->id)
    //                         ->whereNull('finish_time')
    //                         ->first();

    //                     // Jika ada data tes lama tanpa finish_time, selesaikan secara otomatis
    //                     if ($onGoingTest && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($onGoingTest->start_time)->copy()->addSeconds($onGoingTest->time_duration))) {
    //                         $total_score = ResultDetail::where('result_id', $onGoingTest->id)->sum('score');
    //                         $onGoingTest->update([
    //                             'finish_time' => \Carbon\Carbon::now(),
    //                             'total_score' => $total_score,
    //                         ]);
    //                         $onGoingTest = null; // Hapus status tes berjalan
    //                     }

    //                     if (!$onGoingTest) {
    //                         $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
    //                     }
    //                 }
    //             } else {
    //                 $review = Result::where('quiz_id', $data->quiz->id)
    //                     ->where('order_detail_id', $data->id)
    //                     ->whereNotNull('finish_time')
    //                     ->first();
    //                 if ($data->quiz->type_aspect == 'kecermatan') {
    //                     $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                 } else {
    //                     $btn_action .= '<a href="' . route('mytest.review', ['id' => encrypt($review->id)]) . '" class="btn btn-sm btn-primary">Review</a>';
    //                 }
    //             }
    //             if (User::find(Auth::user()->id)->hasRole('admin')) {
    //                 $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete">Hapus</button>';
    //             }
    //             $btn_action .= '</div>';
    //             return $btn_action;
    //         })


    //         ->only(['name', 'package', 'quiz', 'type_quiz', 'action'])
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }


    // function history(Request $request)
    // {
    //     try {
    //         $encryptedId = $request->get('order_detail_id');
    //         $orderDetailId = decrypt($encryptedId); // Dekripsi ID
    //     } catch (\Exception $e) {
    //         abort(403, 'Invalid ID');
    //     }


    //     $detailPackage = OrderDetail::where('id', $orderDetailId)->firstOrFail();
    //     $datatable_route = route('mytest.dataTableHistory', ['order_detail_id' => $encryptedId]);
    //     return view('mytest.history', compact('datatable_route', 'detailPackage'));
    // }

    // function dataTableHistory(Request $request)
    // {
    //     try {
    //         $encryptedId = $request->get('order_detail_id');
    //         $orderDetailId = decrypt($encryptedId); // Dekripsi ID
    //     } catch (\Exception $e) {
    //         abort(403, 'Invalid ID');
    //     }

    //     $result = Result::where('order_detail_id', $orderDetailId)->whereNotNull('finish_time')->get();

    //     return DataTables::of($result)
    //         ->addIndexColumn()
    //         ->addColumn('attempt', function () {
    //             static $attempt = 0;
    //             $attempt++;
    //             return 'Percobaan Ke-' . $attempt;
    //         })
    //         ->addColumn('total_score', function ($data) {
    //             $total_question = $data->details->count();
    //             return $data->total_score / $total_question * 100 . '%';
    //         })
    //         ->addColumn('action', function ($data) {
    //             $btn_action = '<div align="center">';
    //             $btn_action .= '<a href="' . route('mytest.review', ['id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Review</a>';
    //             $btn_action .= '</div>';
    //             return $btn_action;
    //         })
    //         ->only(['total_score', 'attempt', 'action'])
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }


}
