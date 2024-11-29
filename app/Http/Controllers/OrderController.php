<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        $datatable_route = route('order.dataTable');
        return view('order.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $order = Order::whereNull('deleted_at')->where('user_id', Auth::user()->id)->get();

        $dataTable = DataTables::of($order)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                $package_name = $data->package->name;
                return $package_name;
            })
            ->addColumn('total_price', function ($data) {
                $total_price = '<div>' . 'Rp. ' . number_format($data->total_price, 0, ',', '.');
                $total_price .= '</div>';
                return $total_price;
            })
            ->addColumn('status', function ($data) {
                $color = $data->status == 'Belum Dibayar' ? 'text-warning' : 'text-success';
                $status = '<div class="' . $color . '">' . $data->status . '</div>';
                return $status;
            })
            ->addColumn('class', function ($data) {
                return '<div>' . (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-') . '</div>';
            })

            ->addColumn('payment_method', function ($data) {
                $payment = ($data->payment_method == 'non_tunai') ? 'Non Tunai' : 'Tunai';

                return $payment;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="payOrder(' . $data->id . ')" title="Bayar">Bayar</button>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="cancelOrder(' . $data->id . ')" title="Batal">Batal</button>';

                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'total_price', 'class', 'status', 'payment_method', 'action'])
            ->rawColumns(['action', 'total_price', 'status', 'class'])
            ->make(true);

        return $dataTable;
    }

    public function checkout(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $package  = Package::find($id);
            $add_order = Order::lockforUpdate()->create([
                'package_id' => $id,
                'total_price' => $package->price,
                'class' => $package->class,
                'payment_method' => $request->payment_method,
                'user_id' => Auth::user()->id,
                'status' => 'Belum Dibayar',

            ]);


            if ($add_order) {
                DB::commit();
                session()->flash('success', 'Berhasil Checkout Paket');
            } else {
                DB::rollBack();
                session()->flash('success', 'Gagal Checkout Paket');
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $order = Order::find($id);

            if (!is_null($order)) {
                $order_cancel = Order::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($order_cancel) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Membatalkan Order');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Membatalkan Order');
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();
        }
    }
}
