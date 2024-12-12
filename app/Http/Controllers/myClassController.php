<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Result;
use App\Models\ResultDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class myClassController extends Controller
{
    public function index()
    {
        // Ambil semua `order_id` yang sesuai dengan user saat ini
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');

        $orderPackageIdsInClass = ClassAttendance::pluck('order_package_id')->toArray();

        $myClass = OrderPackage::whereIn('order_id', $orderIds)
            ->whereNotIn('id', $orderPackageIdsInClass) // filter
            ->whereNull('deleted_at')
            ->whereNotNull('class')
            ->get();

        $datatable_route = route('myclass.dataTable');
        return view('myclass.index', compact('datatable_route', 'myClass'));
    }
    public function dataTable()
    {
        // Ambil semua `order_id` yang sesuai dengan user saat ini
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');

        // Ambil semua `order_package_id` yang ada di `ClassAttendance`
        $orderPackageIdsInClass = ClassAttendance::pluck('order_package_id')->toArray();

        // Filter `OrderPackage` berdasarkan `order_id` dan `order_package_id` yang ada di `ClassAttendance`
        $myClass = OrderPackage::whereIn('order_id', $orderIds)
            ->whereIn('id', $orderPackageIdsInClass) // filter
            ->whereNull('deleted_at')
            ->whereNotNull('class')
            ->get();

        return DataTables::of($myClass)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('class', function ($data) {
                return (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-');
            })
            ->addColumn('action', function ($data) {
                $encryptedOrderId = encrypt($data->order_id);
                $encryptedPackageId = encrypt($data->package_id);

                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('myclass.detail', ['orderId' => $encryptedOrderId, 'packageId' => $encryptedPackageId]) . '" class="btn btn-sm btn-success">Test</a>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['package', 'class', 'action'])
            ->rawColumns(['action'])
            ->make(true);
    }


    public function detail($orderId, $packageId)
    {
        try {
            $decryptedOrderId = decrypt($orderId);
            $decryptedPackageId = decrypt($packageId);

            $datatable_route = route('myclass.dataTableDetail', ['orderId' => $orderId, 'packageId' => $packageId]);

            $detailPackage = OrderDetail::where('order_id', $decryptedOrderId)
                ->where('package_id', $decryptedPackageId)
                ->whereNull('deleted_at')
                ->first();

            return view('myclass.detail', compact('datatable_route', 'detailPackage'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => 'Invalid ID.']);
        }
    }


    public function dataTableDetail($orderId, $packageId)
    {
        try {
            $decryptedOrderId = decrypt($orderId);
            $decryptedPackageId = decrypt($packageId);

            $detailPackage = OrderDetail::where('order_id', $decryptedOrderId)
                ->where('package_id', $decryptedPackageId)
                ->whereNull('deleted_at')
                ->whereNotNull('open_quiz')
                ->whereNotNull('close_quiz')
                ->get();

            return DataTables::of($detailPackage)
                ->addIndexColumn()
                ->addColumn('quiz', function ($data) {
                    return $data->quiz->name;
                })
                ->addColumn('type_quiz', function ($data) {
                    return $data->quiz->type_aspect;
                })
                ->addColumn('open_quiz', function ($data) {
                    return \Carbon\Carbon::parse($data->open_quiz)->translatedFormat('d F Y H:i');
                })
                ->addColumn('close_quiz', function ($data) {
                    return \Carbon\Carbon::parse($data->close_quiz)->translatedFormat('d F Y H:i');
                })
                ->addColumn('status', function ($data) {
                    $currentDateTime = \Carbon\Carbon::now();
                    $openQuizDateTime = $data->open_quiz
                        ? \Carbon\Carbon::parse($data->open_quiz)
                        : null;
                    $closeQuizDateTime = $data->close_quiz
                        ? \Carbon\Carbon::parse($data->close_quiz)
                        : null;

                    if (
                        $openQuizDateTime && $closeQuizDateTime &&
                        $currentDateTime->gte($openQuizDateTime) &&
                        $currentDateTime->lte($closeQuizDateTime)
                    ) {
                        return '<div class="text-success">Buka</div>';
                    } else {
                        return '<div class="text-danger">Tutup</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $btn_action = '<div align="center">';

                    //waktu ipen dan close
                    $currentDateTime = \Carbon\Carbon::now();
                    $openQuizDateTime = $data->open_quiz
                        ? \Carbon\Carbon::parse($data->open_quiz)
                        : null;
                    $closeQuizDateTime = $data->close_quiz
                        ? \Carbon\Carbon::parse($data->close_quiz)
                        : null;

                    if (
                        $openQuizDateTime && $closeQuizDateTime &&
                        $currentDateTime->gte($openQuizDateTime) &&
                        $currentDateTime->lt($closeQuizDateTime)
                    ) {

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
                            $startTime = \Carbon\Carbon::parse($result->start_time);
                            $endTime = $startTime->copy()->addSeconds($result->time_duration);


                            if ($currentDateTime->lte($endTime)) {
                                $btn_action .= '<a href="' . route('admin.quiz.getQuestion', ['result' => $result->id]) . '" class="btn btn-sm btn-warning">Lanjutkan</a>';
                            } else {
                                // Update finish_time jika waktu habis
                                $total_score = ResultDetail::where('result_id', $result->id)->sum('score');
                                $result->update([
                                    'finish_time' => $endTime,
                                    'total_score' => $total_score
                                ]);

                                // Setelah waktu habis, langsung tampilkan tombol Review
                                $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                            }
                        } elseif ($review) {
                            $btn_action .= '<a href="' . route('admin.quiz.result', ['resultId' => $review->id]) . '" class="btn btn-sm btn-primary">Review</a>';
                        } else {
                            $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                        }
                    }

                    $btn_action .= '</div>';
                    return $btn_action;
                })
                ->only(['package', 'quiz', 'type_quiz', 'open_quiz', 'close_quiz', 'status', 'action'])
                ->rawColumns(['status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid ID'], 403);
        }
    }

    // public function dataTable()
    // {
    //     $orderIds = Order::where('user_id', Auth::user()->id)
    //         ->whereNull('deleted_at')
    //         ->where('status', 100)
    //         ->pluck('id');


    //     $myClass = OrderPackage::whereIn('order_id', $orderIds)
    //         ->whereNull('deleted_at')
    //         ->whereNotNull('class')
    //         ->get();

    //     return DataTables::of($myClass)
    //         ->addIndexColumn()
    //         ->addColumn('package', function ($data) {
    //             return $data->package->name;
    //         })
    //         ->addColumn('class', function ($data) {
    //             return (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-');
    //         })

    //         ->addColumn('action', function ($data) {
    //             $encryptedOrderId = encrypt($data->order_id);
    //             $encryptedPackageId = encrypt($data->package_id);

    //             $btn_action = '<div align="center">';
    //             $btn_action .= '<a href="' . route('myclass.detail', ['orderId' => $encryptedOrderId, 'packageId' => $encryptedPackageId]) . '" class="btn btn-sm btn-success">Test</a>';
    //             $btn_action .= '</div>';
    //             return $btn_action;
    //         })

    //         ->only(['package', 'class', 'action'])
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }
}
