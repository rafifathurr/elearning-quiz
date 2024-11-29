<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        if (User::find(Auth::user()->id)->hasRole('user')) {
            $order = Order::whereNull('deleted_at')->where('user_id', Auth::user()->id)->get();
        } else {
            $order = Order::whereNull('deleted_at')->get();
        }

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
                $color = 'text-secondary';

                if ($data->status == 'Belum Dibayar') {
                    $color = 'text-warning';
                } elseif ($data->status == 'Menunggu Konfirmasi') {
                    $color = 'text-primary';
                } elseif ($data->status == 'Berhasil Dikonfirmasi') {
                    $color = 'text-success';
                } elseif ($data->status == 'Bukti Ditolak') {
                    $color = 'text-danger';
                }

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
            ->addColumn('attachment', function ($data) {
                if (!is_null($data->attachment)) {
                    return '<a href="' . asset($data->attachment) . '" target= "_blank"><i class="fas fa-download mr-1"></i> Bukti Pembayaran</a>';
                }
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                if (User::find(Auth::user()->id)->hasRole('user')) {
                    if (is_null($data->attachment) || $data->status == 'Bukti Ditolak') {
                        $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="payOrder(' . $data->id . ', \'' . addslashes($data->package->name) . '\')"  title="Bayar">Bayar</button>';
                        $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="cancelOrder(' . $data->id . ')" title="Batal">Batal</button>';
                    }
                } elseif (User::find(Auth::user()->id)->hasRole('admin')) {
                    if (!is_null($data->attachment) && $data->status == 'Menunggu Konfirmasi') {
                        $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="approveOrder(' . $data->id . ')" title="Terima">Terima</button>';
                        $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="rejectOrder(' . $data->id . ')" title="Tolak">Tolak</button>';
                    } else if ($data->status == 'Berhasil Dikonfirmasi') {
                        $btn_action .= '<span class="text-success ml-2">Bukti Diterima</span>';
                    } else if ($data->status == 'Bukti Ditolak') {
                        $btn_action .= '<span class="text-danger ml-2">Bukti Ditolak</span>';
                    } else {
                        $btn_action .= '<span class="text-warning ml-2">Belum Dibayar</span>';
                    }
                }
                $btn_action .= '</div>';
                return $btn_action;
            })

            ->only(['name', 'total_price', 'class', 'status', 'payment_method', 'attachment', 'action'])
            ->rawColumns(['action', 'total_price', 'status', 'class', 'attachment'])
            ->make(true);

        return $dataTable;
    }

    public function checkout(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

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
                session()->flash('failed', 'Gagal Checkout Paket');
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    public function payment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            if ($order) {

                $request->validate([
                    'upload_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                if ($request->hasFile('upload_image')) {

                    $path = 'public/order/images' .  $id;
                    $path_store = 'storage/order/images' .  $id;

                    if (!Storage::exists($path)) {
                        Storage::makeDirectory($path);
                    }

                    $file_name = $id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('upload_image')->getClientOriginalExtension();

                    if (Storage::exists($path . '/' . $file_name)) {
                        Storage::delete($path . '/' . $file_name);
                    }
                    $request->file('upload_image')->storePubliclyAs($path, $file_name);

                    $attachment = $path_store . '/' . $file_name;

                    $order->update([
                        'payment_date' => now(),
                        'status' => 'Menunggu Konfirmasi',
                        'attachment' => $attachment,
                    ]);
                    DB::commit();
                    session()->flash('success', 'Berhasil Upload Bukti Pembayaran');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Upload Bukti Pembayaran');
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Data Order Tidak Ditemukan']);
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function approve(string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            if ($order) {
                $approve_order = $order->update([
                    'status' => 'Berhasil Dikonfirmasi',
                ]);
                if ($approve_order) {
                    $order_detail = [];
                    foreach ($order->package->packageTest as $packageTest) {
                        $quiz = $packageTest->quiz;
                        if ($quiz) {
                            $order_detail[] = [
                                'order_id' => $id,
                                'package_id' => $order->package_id,
                                'quiz_id' => $quiz->id
                            ];
                        }
                    }
                    $add_order_detail = OrderDetail::insert($order_detail);

                    if ($add_order_detail) {
                        DB::commit();
                        session()->flash('success', 'Berhasil Menerima Order');
                    } else {
                        DB::rollBack();
                        session()->flash('failed', 'Gagal Menerima Order');
                    }
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menerima Order');
                }
            } else {
                session()->flash('failed', 'Data Tidak Ditemukan');
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function reject(string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);

            $reject_order = $order->update([
                'status' => 'Bukti Ditolak'
            ]);
            if ($reject_order) {
                DB::commit();
                session()->flash('success', 'Berhasil Menolak Order');
            } else {
                DB::rollBack();
                session()->flash('failed', 'Gagal Menolak Order');
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
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
