<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index');
    }
    public function checkout(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $package  = Package::find($id);
            $add_order = Order::lockforUpdate()->create([
                'package_id' => $id,
                'total_price' => $package->price,
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
}
