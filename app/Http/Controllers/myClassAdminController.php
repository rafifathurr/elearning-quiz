<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class myClassAdminController extends Controller
{
    public function index()
    {
        $datatable_route = route('class.dataTable');
        return view('counselor.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $myClass = ClassPackage::whereNull('deleted_at')->where('user_id', Auth::user()->id)->get();

        $dataTable = DataTables::of($myClass)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('total_meeting', function ($data) {
                return $data->total_meeting . ' Pertemuan';
            })
            ->addColumn('current_meeting', function ($data) {
                return $data->current_meeting == 0 ? 'Belum Ada Pertemuan' : 'Pertemuan Ke-' . $data->current_meeting;
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('class.show', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Detail"> <i class="fas fa-search mr-1"></i> Detail</a>';
                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['package', 'action', 'current_meeting', 'total_meeting'])
            ->rawColumns(['action', 'current_meeting', 'total_meeting'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $classPackageId = ClassPackage::whereNull('deleted_at')
            ->whereColumn('current_meeting', '<', 'total_meeting')
            ->pluck('package_id');

        $packages = Package::whereNull('deleted_at')
            ->whereNotNull('class')
            ->whereNotIn('id', $classPackageId)
            ->get();

        return view('counselor.create', compact('packages'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $package = Package::find($request->package_id);
            $add_class = ClassPackage::lockForUpdate()->create([
                'package_id' => $request->package_id,
                'total_meeting' => $package->class,
                'current_meeting' => 0,
                'user_id' => Auth::user()->id,

            ]);

            if ($add_class) {
                DB::commit();
                return redirect()->route('class.index')->with(['success' => 'Berhasil Menambahkan Kelas']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Kelas'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }


    public function show($id)
    {
        try {

            $class = ClassPackage::find($id);

            $listOrder = OrderPackage::whereHas('order', function ($query) {
                $query->whereNull('deleted_at')->where('status', 100);
            })->whereNull('deleted_at')
                ->where('package_id', $class->package_id)
                ->get();

            $listClass = ClassAttendance::where('class_id', $id)
                ->select('order_package_id')
                ->distinct()
                ->with(['orderPackage.order.user'])
                ->get();


            return view('counselor.detail', compact('class', 'listClass', 'listOrder'));
        } catch (Exception $e) {

            dd($e->getMessage());
        }
    }




    public function storeMember(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'class_id' => 'required|exists:class_packages,id',
                'order_package_id.*' => 'nullable|exists:order_packages,id'
            ]);

            $class_attendance = [];

            $class = ClassPackage::find($request->class_id);
            $currentMeeting = $class->current_meeting ?? 0;
            $class_update = $class->update([
                'current_meeting' => $currentMeeting + 1
            ]);


            if (isset($request->order_package_id)) {
                foreach ($request->order_package_id as $order) {
                    $class_attendance[] = [
                        'order_package_id' => $order,
                        'class_id' => $request->class_id,
                        'attendance' => 'present',
                        'attendance_date' => now()
                    ];
                }
            } else {
                $present = $request->has('present') ? 'present' : 'not present';

                ClassAttendance::where('class_id', $request->class_id)
                    ->select('order_package_id', 'class_id') // Pilih kolom yang relevan
                    ->distinct() // Hindari data duplikat berdasarkan kolom yang dipilih
                    ->chunk(100, function ($attendances) use (&$class_attendance, $present) {
                        foreach ($attendances as $attendance) {
                            $class_attendance[] = [
                                'order_package_id' => $attendance->order_package_id,
                                'class_id' => $attendance->class_id,
                                'attendance' => $present,
                                'attendance_date' => now()
                            ];
                        }
                    });
            }

            if (empty($class_attendance)) {
                DB::rollBack();
                return redirect()->back()->with(['failed' => 'Tidak ada data kehadiran untuk ditambahkan.']);
            }

            $add_class_attendance = ClassAttendance::insert($class_attendance);


            if (Session::has('test')) {
                $test = Session::get('test');


                foreach ($class_attendance as $attendance) {
                    $orderPackage = OrderPackage::find($attendance['order_package_id']);

                    if (!$orderPackage) {
                        DB::rollBack();
                        return redirect()->back()->with(['failed' => 'Order Package tidak ditemukan.']);
                    }

                    $updated = OrderDetail::where('order_id', $orderPackage->order_id)
                        ->where('package_id', $orderPackage->package_id)
                        ->where('quiz_id', $test['quiz_id'])
                        ->update([
                            'open_quiz' => $test['open_quiz'],
                            'close_quiz' => $test['close_quiz'],
                        ]);

                    if (!$updated) {
                        DB::rollBack();
                        return redirect()->back()->with(['failed' => 'Gagal memperbarui detail order untuk Order ID: ' . $orderPackage->order_id]);
                    }
                }


                Session::forget('test');
            }



            if ($add_class_attendance && $class_update) {
                DB::commit();
                return redirect()->back()->with(['success' => 'Berhasil Menambahkan Anggota Kelas']);
            } else {
                DB::rollBack();
                return redirect()->back()->with(['failed' => 'Gagal Menambahkan Anggota Kelas']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('failed', $e->getMessage());
            return redirect()->back();
        }
    }


    public function storeTest(Request $request)
    {

        DB::beginTransaction();
        try {
            $request->validate([
                'open_quiz' => 'required|date',
                'close_quiz' => 'required|date|after:open_quiz',
            ]);

            $exist_class_attendance = ClassAttendance::where('class_id', $request->class_id)->exists();

            if (!$exist_class_attendance) {
                $data = [
                    'open_quiz' => $request->open_quiz,
                    'close_quiz' => $request->close_quiz,
                    'quiz_id' => $request->quiz_id,
                ];
                Session::put('test', $data);
                session()->flash('success', 'Belum ada anggota kelas, Test disimpan di session');
            }

            $class_attendances = ClassAttendance::where('class_id', $request->class_id)->get();

            foreach ($class_attendances as $attendance) {
                $user_order = OrderDetail::where('order_id', $attendance->orderPackage->order_id)
                    ->where('package_id', $attendance->orderPackage->package_id)
                    ->where('quiz_id', $request->quiz_id)
                    ->update([
                        'open_quiz' => $request->open_quiz,
                        'close_quiz' => $request->close_quiz,
                    ]);

                if ($user_order) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menambahkan Test');
                }

                if (!$user_order) {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menambahkan Test');
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('failed', $e->getMessage());
        }
    }





    // public function storeClass(Request $request)
    // {
    //     DB::beginTransaction(); // Mulai transaksi
    //     try {
    //         // Validasi input: pastikan user ID ada di tabel orders
    //         $request->validate([
    //             'user' => 'required|array|min:1',
    //             'user.*' => 'exists:orders,user_id',
    //         ]);

    //         // Ambil semua orders berdasarkan user_id yang dikirim
    //         $orders = Order::whereIn('user_id', $request->user)->get();

    //         foreach ($orders as $order) {
    //             // Cari orderPackage berdasarkan order_id
    //             $orderPackage = OrderPackage::where('order_id', $order->id)->where('package_id', $request->package_id)->first();

    //             if ($orderPackage) {
    //                 // Update current_class
    //                 $orderPackage->update([
    //                     'current_class' => $orderPackage->current_class + 1,
    //                 ]);
    //             }
    //         }

    //         DB::commit(); // Commit transaksi
    //         return redirect()
    //             ->back()
    //             ->with(['success' => 'Berhasil Menambahkan Kelas']);
    //     } catch (Exception $e) {
    //         DB::rollBack(); // Rollback jika terjadi kesalahan
    //         return redirect()
    //             ->back()
    //             ->with(['failed' => $e->getMessage()]);
    //     }
    // }
}
