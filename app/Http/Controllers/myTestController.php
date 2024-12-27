<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (User::find(Auth::user()->id)->hasRole('user')) {
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
                ->get();
        }


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

                if (User::find(Auth::user()->id)->hasRole('user')) {
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
                            if ($data->quiz->type_aspect == 'kecermatan') {
                                $btn_action .= '<a href="' . route('kecermatan.getQuestion', ['result' => $result->id]) . '" class="btn btn-sm btn-warning">Lanjutkan</a>';
                            } else {
                                $btn_action .= '<a href="' . route('admin.quiz.getQuestion', ['result' => $result->id]) . '" class="btn btn-sm btn-warning">Lanjutkan</a>';
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
                        $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                    }
                } else {
                    $review = Result::where('quiz_id', $data->quiz->id)
                        ->where('order_detail_id', $data->id)
                        ->whereNotNull('finish_time')
                        ->first();
                    if ($data->quiz->type_aspect == 'kecermatan') {
                        $btn_action .= '<a href="' . route('kecermatan.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                    } else {
                        $btn_action .= '<a href="' . route('mytest.review', ['id' => encrypt($review->id)]) . '" class="btn btn-sm btn-primary">Review</a>';
                    }
                }
                $btn_action .= '</div>';
                return $btn_action;
            })


            ->only(['name', 'package', 'quiz', 'type_quiz', 'action'])
            ->rawColumns(['action'])
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
