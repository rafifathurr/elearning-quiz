<?php

namespace App\Http\Controllers;

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
        $datatable_route = route('myclass.dataTable');
        return view('myclass.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');


        $myClass = OrderPackage::whereIn('order_id', $orderIds)
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
                ->get();

            return DataTables::of($detailPackage)
                ->addIndexColumn()
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

                    if ($result) {
                        $currentDateTime = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($result->start_time);
                        $endTime = $startTime->copy()->addSeconds($result->time_duration);

                        // Jika waktu sekarang lebih dari endTime, update finish_time
                        if ($currentDateTime->gt($endTime)) {
                            $total_score = ResultDetail::where('result_id', $result->id)->sum('score');
                            $result->update([
                                'finish_time' => $endTime,
                                'total_score' => $total_score
                            ]);
                        }
                    }

                    // Cek ulang apakah result ada dan belum selesai
                    $result = Result::where('quiz_id', $data->quiz->id)
                        ->where('user_id', Auth::user()->id)
                        ->where('order_detail_id', $data->id)
                        ->whereNull('finish_time')
                        ->first();

                    if ($result) {
                        $currentDateTime = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($result->start_time);
                        $endTime = $startTime->copy()->addSeconds($result->time_duration);

                        if ($currentDateTime->lte($endTime)) {
                            $btn_action .= '<a href="' . route('admin.quiz.getQuestion', ['result' => $result->id]) . '" class="btn btn-sm btn-warning">Lanjutkan</a>';
                        } else {
                            $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                        }
                    } else {
                        $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->quiz->id), 'order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                    }
                    $hasHistory = Result::where('quiz_id', $data->quiz->id)
                        ->where('user_id', Auth::user()->id)
                        ->where('order_detail_id', $data->id)
                        ->whereNotNull('finish_time')
                        ->exists();

                    if ($hasHistory) {
                        $btn_action .= '<a href="' . route('mytest.history', ['order_detail_id' => encrypt($data->id)]) . '" class="btn btn-sm btn-primary mx-2">Riwayat</a>';
                    }

                    $btn_action .= '</div>';
                    return $btn_action;
                })
                ->only(['package', 'quiz', 'type_quiz', 'action'])
                ->rawColumns(['action'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid ID'], 403);
        }
    }
}
