<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Package;
use Carbon\Carbon;
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
            ->where('user_id', Auth::user()->id)
            ->pluck('package_id');

        $packages = Package::whereNull('deleted_at')
            ->where('class', '>', 0)
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
                return redirect()->route('class.show', ['id' => $add_class->id])->with(['success' => 'Berhasil Menambahkan Kelas']);
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


    public function show($id, Request $request)
    {
        try {
            $class = ClassPackage::find($id);

            if ($class->user_id != Auth::user()->id) {
                return redirect()
                    ->back()
                    ->with('failed', 'Anda Tidak Bisa Akses Kelas Ini');
            }


            $orderPackageIdInAttendance = ClassAttendance::whereHas('class', function ($query) {
                $query->whereColumn('current_meeting', '<', 'total_meeting');
            })
                ->pluck('order_package_id');


            $listOrder = OrderPackage::whereHas('order', function ($query) {
                $query->whereNull('deleted_at')
                    ->where('status', 100);
            })
                ->whereNull('deleted_at')
                ->where('package_id', $class->package_id)
                ->whereNotIn('id', $orderPackageIdInAttendance)
                ->get();



            $filterDate = ClassAttendance::where('class_id', $id)
                ->select('attendance_date')
                ->distinct()
                ->orderBy('attendance_date', 'asc')
                ->get();

            // Filter data berdasarkan tanggal yang dipilih
            $selectedDate = $request->get('filter_data');

            // Cek apakah ada kehadiran terakhir untuk hari yang sama
            $currentDate = Carbon::now()->format('Y-m-d');
            $latestAttendance = ClassAttendance::where('class_id', $id)
                ->whereDate('attendance_date', $currentDate)
                ->latest('attendance_date')
                ->first();

            if ($latestAttendance) {
                if ($selectedDate) {
                    $listClass = ClassAttendance::where('class_id', $id)
                        ->when($selectedDate, function ($query, $selectedDate) {
                            return $query->where('attendance_date', $selectedDate);
                        })
                        ->with(['orderPackage.order.user'])
                        ->get();
                } else {
                    $listClass = ClassAttendance::where('class_id', $id)
                        ->where('attendance_date', $latestAttendance->attendance_date)
                        ->with(['orderPackage.order.user'])
                        ->get();
                }
            } elseif ($selectedDate) {
                $listClass = ClassAttendance::where('class_id', $id)
                    ->when($selectedDate, function ($query, $selectedDate) {
                        return $query->where('attendance_date', $selectedDate);
                    })
                    ->with(['orderPackage.order.user'])
                    ->get();
            } else {
                $listClass = ClassAttendance::where('class_id', $id)
                    ->select('order_package_id')
                    ->distinct()
                    ->with(['orderPackage.order.user'])
                    ->get();
            }


            $listMember = null;
            if (Session::has('new_member')) {
                $listMember = Session::get('new_member');
            }

            return view('counselor.detail', compact('class', 'listClass', 'listOrder', 'filterDate', 'listMember', 'selectedDate', 'latestAttendance'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }


    public function storeAttendance(Request $request)
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
                        'attendance' => 1,
                        'attendance_date' => now()
                    ];
                }
            } else {
                $attendances = $request->input('attendance', []); // Default kosong jika tidak ada data
                $class_order_packages = ClassAttendance::where('class_id', $request->class_id)
                    ->select('order_package_id', 'class_id')
                    ->distinct()
                    ->get();
                foreach ($class_order_packages as $attendance) {
                    $class_attendance[] = [
                        'order_package_id' => $attendance->order_package_id,
                        'class_id' => $attendance->class_id,
                        'attendance' => isset($attendances[$attendance->order_package_id]) ? 1 : 0,
                        'attendance_date' => now(),
                    ];
                }
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
                Session::forget('new_member');
                DB::commit();
                return redirect()->back()->with(['success' => 'Berhasil Melakukan Absensi']);
            } else {
                DB::rollBack();
                return redirect()->back()->with(['failed' => 'Gagal Melakukan Absensi']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back();
        }
    }

    public function updateAttendance(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'class_id' => 'required|exists:class_packages,id',
                'attendance' => 'nullable|array',
            ]);

            $attendances = $request->input('attendance', []); // Default kosong jika tidak ada data

            $currentDate = Carbon::now()->format('Y-m-d');
            $latestAttendance = ClassAttendance::where('class_id', $request->class_id)
                ->whereDate('attendance_date', $currentDate)
                ->latest('attendance_date')
                ->first();

            if (!$latestAttendance) {
                return redirect()->back()->with(['failed' => 'Tidak ada data absensi untuk hari ini.']);
            }

            $class_order_packages = ClassAttendance::where('class_id', $request->class_id)
                ->where('attendance_date', $latestAttendance->attendance_date)
                ->get();

            foreach ($class_order_packages as $attendance) {
                $attendance->update([
                    'attendance' => isset($attendances[$attendance->order_package_id]) ? 1 : 0,
                ]);
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Mengubah Absensi']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['failed' => 'Gagal Mengubah Absensi: ' . $e->getMessage()]);
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


    public function storeMember(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'order_package_id' => 'required|array', // Harus array
                'order_package_id.*' => 'exists:order_packages,id', // Validasi ID di database
                'class_id' => 'required|exists:class_packages,id' // Validasi class_id di database
            ]);

            // Ambil data yang sudah ada di session
            $existingMembers = Session::get('new_member', []); // Ambil data lama, default []

            // Data baru
            $order_packages = [];
            foreach ($validatedData['order_package_id'] as $package) {
                $orderPackage = OrderPackage::with('order')->find($package);

                // Periksa apakah kombinasi class_id dan order_package_id sudah ada
                $isDuplicate = collect($existingMembers)->contains(function ($member) use ($validatedData, $orderPackage) {
                    return $member['class_id'] == $validatedData['class_id'] && $member['order_package_id'] == $orderPackage->id;
                });

                // Jika tidak duplikat, tambahkan ke array baru
                if (!$isDuplicate) {
                    $order_packages[] = [
                        'order_package_id' => $orderPackage->id,
                        'class_id' => $validatedData['class_id'],
                        'user_name' => $orderPackage->order->user->name
                    ];
                }
            }

            // Gabungkan data baru dengan data lama
            Session::put('new_member', array_merge($existingMembers, $order_packages));

            // Periksa hasil dari sesi
            $updatedMembers = Session::get('new_member');

            if (!empty($order_packages)) {
                return redirect()
                    ->back()
                    ->with(['success' => 'Peserta Berhasil Ditambahkan']);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Tidak ada peserta baru yang ditambahkan (semua data sudah ada).']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function removeMember($index)
    {
        $members = Session::get('new_member', []);
        if (isset($members[$index])) {
            unset($members[$index]);
            Session::put('new_member', array_values($members)); // Reset array index
            session()->flash('success', 'Berhasil Hapus Data Peserta');
        }
        session()->flash('failed', 'Gagal Hapus Data Peserta');
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
