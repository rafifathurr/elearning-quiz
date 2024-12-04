<?php

namespace App\Http\Controllers;

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
        return view('adminclass.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $myClass = Package::whereNull('deleted_at')->whereNotNull('class')->get();

        $dataTable = DataTables::of($myClass)
            ->addIndexColumn()
            ->addColumn('class', function ($data) {
                return (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-');
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('class.detail', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Detail">Detail</a>';
                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'class', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    public function detail($id)
    {
        try {
            $orderId = Order::where('status', 100)->pluck('id');
            $packageOrder = OrderPackage::whereIn('order_id', $orderId)->where('package_id', $id)->whereNull('deleted_at')->whereColumn('current_class', '<=', 'class')->get();
            if ($packageOrder->isEmpty()) {
                session()->flash('failed', 'Belum Ada Pembeli Paket');
                return redirect()->back();
            }
            return view('adminclass.detail', compact('packageOrder'));
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi
        try {
            // Validasi input: pastikan user ID ada di tabel orders
            $request->validate([
                'user' => 'required|array|min:1',
                'user.*' => 'exists:orders,user_id',
            ]);

            // Ambil semua orders berdasarkan user_id yang dikirim
            $orders = Order::whereIn('user_id', $request->user)->get();

            foreach ($orders as $order) {
                // Cari orderPackage berdasarkan order_id
                $orderPackage = OrderPackage::where('order_id', $order->id)->where('package_id', $request->package_id)->first();

                if ($orderPackage) {
                    // Update current_class
                    $orderPackage->update([
                        'current_class' => $orderPackage->current_class + 1,
                    ]);
                }
            }

            DB::commit(); // Commit transaksi
            return redirect()
                ->back()
                ->with(['success' => 'Berhasil Menambahkan Kelas']);
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }
}
