<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\ClassUser;
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

        $orderPackageIdsInClass = ClassUser::pluck('order_package_id')->toArray();

        $myClass = OrderPackage::whereIn('order_id', $orderIds)
            ->whereNotIn('id', $orderPackageIdsInClass) // filter
            ->whereNull('deleted_at')
            ->where('class', '>', 0)
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

        // Ambil data dari `ClassUser` berdasarkan `order_id`
        $classUsers = ClassUser::whereHas('orderPackage', function ($query) use ($orderIds) {
            $query->whereIn('order_id', $orderIds)
                ->whereNull('deleted_at')
                ->where('class', '>', 0);
        })
            ->get();

        return DataTables::of($classUsers)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return optional($data->orderPackage->package)->name ?? '-';
            })
            ->addColumn('class', function ($data) {
                return (!is_null($data->orderPackage->class) ? $data->orderPackage->class . 'x Pertemuan' : '-');
            })
            ->addColumn('class_name', function ($data) {
                return optional($data->class)->name ?? '-';
            })
            ->addColumn('class_counselor', function ($data) {
                if ($data->orderPackage->classPackage) {
                    $list_view = '<div class="text-justify">';
                    foreach ($data->orderPackage->classPackage->classCounselor as $item) {
                        $list_view .= '<span class="badge bg-primary p-2 m-2" style="font-size: 0.9rem; font-weight: bold;">' . $item->counselor->name . '</span>';
                    }
                    $list_view .= '</div>';
                    return $list_view;
                }
                return '-';
            })
            ->addColumn('action', function ($data) {
                $encryptedOrderId = encrypt($data->orderPackage->order_id);
                $encryptedPackageId = encrypt($data->orderPackage->package_id);
                $encryptedOrderPackageId = encrypt($data->order_package_id);
                $encryptedClassId = encrypt($data->class->id);

                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('myclass.detail', ['orderId' => $encryptedOrderId, 'packageId' => $encryptedPackageId, 'classId' => $encryptedClassId]) . '" class="btn btn-sm btn-success m-1">Test</a>';
                $btn_action .= '<a href="' . route('myclass.dataTableAttendance', ['orderPackageId' => $encryptedOrderPackageId]) . '" class="btn btn-sm btn-primary m-1">Absensi</a>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['package', 'class', 'class_name', 'action', 'class_counselor'])
            ->rawColumns(['action', 'class_counselor'])
            ->make(true);
    }


    public function dataTableAttendance(Request $request, $orderPackageId)
    {
        if ($request->ajax()) {
            $decryptedOrderPackageId = decrypt($orderPackageId);
            $attendance = ClassAttendance::where('order_package_id', $decryptedOrderPackageId)->get();

            return DataTables::of($attendance)
                ->addIndexColumn()
                ->addColumn('attendance_date', function ($data) {
                    return \Carbon\Carbon::parse($data->attendance_date)->translatedFormat('l, d F Y');
                })
                ->addColumn('counselor', function ($data) {
                    return $data->counselor ? $data->counselor->name : '';
                })
                ->addColumn('status', function ($data) {
                    $status = null;
                    if ($data->attendance == 0) {
                        $status = '<span class="badge p-2 bg-danger" style="font-size: 1rem; font-weight: bold;">Tidak Hadir</span>';
                    } else {
                        $status = '<span class="badge p-2 bg-success" style="font-size: 1rem; font-weight: bold;">Hadir</span>';
                    }
                    return $status;
                })

                ->only(['attendance_date', 'counselor', 'status'])
                ->rawColumns(['status'])
                ->make(true);
        }
        $decryptedOrderPackageId = decrypt($orderPackageId);
        // Mengambil class_id pertama kali ditemukan
        $classId = ClassUser::where('order_package_id', $decryptedOrderPackageId)
            ->value('class_id');
        $class = ClassPackage::find($classId);
        return view('myclass.attendance', compact('class'));
    }

    public function detail($orderId, $packageId, $classId)
    {
        try {
            $decryptedOrderId = decrypt($orderId);
            $decryptedPackageId = decrypt($packageId);
            $decryptedClassId = decrypt($classId);

            $datatable_route = route('myclass.dataTableDetail', ['orderId' => $orderId, 'packageId' => $packageId, 'classId' => $classId]);

            $detailPackage = OrderDetail::where('order_id', $decryptedOrderId)
                ->where('package_id', $decryptedPackageId)
                ->where('class_id', $decryptedClassId)
                ->whereNull('deleted_at')
                ->first();
            $className = OrderPackage::where('order_id', $decryptedOrderId)
                ->where('package_id', $decryptedPackageId)
                ->whereNull('deleted_at')
                ->first();
            return view('myclass.detail', compact('datatable_route', 'detailPackage', 'className'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => 'Invalid ID.']);
        }
    }


    public function dataTableDetail($orderId, $packageId, $classId)
    {
        try {
            $decryptedOrderId = decrypt($orderId);
            $decryptedPackageId = decrypt($packageId);
            $decryptedClassId = decrypt($classId);

            $detailPackage = OrderDetail::where('order_id', $decryptedOrderId)
                ->where('package_id', $decryptedPackageId)
                ->where('class_id', $decryptedClassId)
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
                ->addColumn('open_quiz', function ($data) {
                    return $data->open_quiz
                        ? \Carbon\Carbon::parse($data->open_quiz)->translatedFormat('d F Y H:i')
                        : '-';
                })
                ->addColumn('close_quiz', function ($data) {
                    return $data->close_quiz
                        ? \Carbon\Carbon::parse($data->close_quiz)->translatedFormat('d F Y H:i')
                        : '-';
                })
                ->addColumn('status', function ($data) {
                    $currentDateTime = \Carbon\Carbon::now();
                    $openQuizDateTime = $data->open_quiz ? \Carbon\Carbon::parse($data->open_quiz) : null;
                    $closeQuizDateTime = $data->close_quiz ? \Carbon\Carbon::parse($data->close_quiz) : null;

                    if ($openQuizDateTime === null || ($openQuizDateTime !== null && $closeQuizDateTime === null)) {
                        return '<div class="text-success">Buka</div>';
                    }

                    if ($currentDateTime->gte($openQuizDateTime) && $currentDateTime->lte($closeQuizDateTime)) {
                        return '<div class="text-success">Buka</div>';
                    }

                    return '<div class="text-danger">Tutup</div>';
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
                        ($openQuizDateTime === null && $closeQuizDateTime === null) ||
                        ($openQuizDateTime !== null && $closeQuizDateTime === null) ||
                        ($openQuizDateTime !== null && $closeQuizDateTime !== null &&
                            $currentDateTime->gte($openQuizDateTime) && $currentDateTime->lt($closeQuizDateTime))
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
    //         ->where('class', '>', 0)
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
