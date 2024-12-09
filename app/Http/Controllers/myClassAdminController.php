<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->addColumn('current_meeting', function ($data) {
                return $data->current_meeting == 0 ? 'Belum Ada Pertemuan' : 'Pertemuan Ke-' . $data->current_meeting;
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('class.show', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Detail">Detail</a>';
                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['package', 'action', 'current_meeting'])
            ->rawColumns(['action', 'current_meeting'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $packages  = Package::whereNull('deleted_at')->whereNotNull('class')->get();

        return view('counselor.create', compact('packages'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $add_class = ClassPackage::lockForUpdate()->create([
                'package_id' => $request->package_id,
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

            $listOrder = OrderPackage::whereNull('deleted_at')->where('package_id', $class->package_id)->get();

            $listClass = ClassAttendance::where('class_id', $id)->get();

            return view('counselor.detail', compact('class', 'listClass', 'listOrder'));
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }



    public function storeMember(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi
        try {
            // Validasi input: pastikan user ID ada di tabel orders
            $request->validate([
                'order_package_id' => 'required|array|min:1',
            ]);

            $class_attendance = [];

            foreach ($request->order_package_id as $order) {
                $class_attendance[] = [
                    'order_package_id' => $order,
                    'class_id' => $request->class_id,
                ];
            };

            $add_class_attendance = ClassAttendance::insert($class_attendance);

            if ($add_class_attendance) {
                DB::commit(); // Commit transaksi
                return redirect()
                    ->back()
                    ->with(['success' => 'Berhasil Menambahkan Anggota Kelas']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['back' => 'Gagal Menambahkan Anggota Kelas']);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
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
