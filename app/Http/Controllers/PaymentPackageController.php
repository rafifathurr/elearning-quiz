<?php

namespace App\Http\Controllers;

use App\Models\PaymentPackage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentPackageController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.payment.dataTable');
        return view('master.payment.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $payments = PaymentPackage::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($payments)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.payment.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit">Edit</a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete">Delete</button>';

                $btn_action .= '<div>';
                return $btn_action;
            })
            ->addColumn('price', function ($data) {
                $price = '<div>' . 'Rp. ' . number_format($data->price, 0, ',', '.');

                $price .= '<div>';
                return $price;
            })
            ->only(['name', 'description', 'price', 'quota_access', 'action'])
            ->rawColumns(['action', 'description', 'price'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        return view('master.payment.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'description' => 'nullable',
                'quota_access' => 'required',
                'price' => 'required|numeric',
            ]);

            $payment = PaymentPackage::lockForUpdate()->create([
                'name' => $request->name,
                'description' => $request->description,
                'quota_access' => $request->quota_access,
                'price' => $request->price
            ]);
            if ($payment) {
                DB::commit();
                return redirect()
                    ->route('master.payment.index')
                    ->with(['success' => 'Berhasil Menambahkan Paket Pembayaran']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Paket Pembayaran'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return
                redirect()
                ->back()
                ->with(['failed', $e->getMessage()]);
        }
    }

    public function edit(String $id)
    {
        $payment = PaymentPackage::find($id);

        return view('master.payment.edit', compact('payment'));
    }
    public function update(String $id, Request $request)
    {
        try {
            $payment = PaymentPackage::find($id);

            if (!is_null($payment)) {
                $request->validate([
                    'name' => 'required',
                    'description' => 'nullable',
                    'quota_access' => 'required',
                    'price' => 'required|numeric',
                ]);

                DB::beginTransaction();
                $payment_update = PaymentPackage::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'quota_access' => $request->quota_access,
                        'price' => $request->price
                    ]);

                if ($payment_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.payment.index')
                        ->with(['success' => 'Berhasil Mengubah Paket Pembayaran']);
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Mengubah Paket Pembayaran'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Mengubah Paket Pembayaran'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return
                redirect()
                ->back()
                ->with(['failed', $e->getMessage()]);
        }
    }

    public function destroy(String $id)
    {
        try {
            DB::beginTransaction();
            $payment  = PaymentPackage::find($id);

            if (!is_null($payment)) {
                $payment_delete = PaymentPackage::where('id', $id)
                    ->update([
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                if ($payment_delete) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Paket Pembayaran');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Berhasil Menghapus Paket Pembayaran');
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menghapus Paket Pembayaran'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return
                redirect()
                ->back()
                ->with(['failed', $e->getMessage()]);
        }
    }
}
