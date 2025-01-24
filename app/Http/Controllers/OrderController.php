<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        if (User::find(Auth::user()->id)->hasRole('user')) {
            $datatable_route = route('order.dataTable');
        } else {
            $datatable_route = route('order.dataTable2');
        }
        return view('order.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $order_ids = Order::whereNull('deleted_at')
            ->where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->pluck('id');

        $order_package = OrderPackage::whereNull('deleted_at')
            ->whereIn('order_id', $order_ids)
            ->get();

        $totalPrice = $order_package->sum(function ($data) {
            return $data->package->price;
        });

        $order_id = null;
        if ($order_package->isNotEmpty()) {
            $order_id = $order_package->first()->order_id;
        }

        return DataTables::of($order_package)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return $data->package->name;
            })
            ->addColumn('class', function ($data) {
                return (!is_null($data->class) && $data->class > 0 ? $data->class . 'x Pertemuan' : '-');
            })
            ->addColumn('price', function ($data) {
                return 'Rp. ' . number_format($data->package->price, 0, ',', '.');
            })

            ->addColumn('action', function ($data) {
                return '<div align="center">
                            <button class="btn btn-sm btn-danger ml-2" onclick="cancelOrder(' . $data->id . ')" title="Hapus">Hapus</button>
                        </div>';
            })
            ->addColumn('order_id', function () use ($order_id) {
                return $order_id ? $order_id : '-';
            })

            ->with('totalPrice', 'Rp. ' . number_format($totalPrice, 0, ',', '.'))
            ->rawColumns(['price', 'action'])
            ->make(true);
    }

    public function dataTable2()
    {
        $order = Order::whereNull('deleted_at')->where('status', 10)->get();

        return DataTables::of($order)
            ->addIndexColumn()
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->addColumn('total_price', function ($data) {
                return 'Rp. ' . number_format($data->total_price, 0, ',', '.');
            })
            ->addColumn('payment_method', function ($data) {
                $payment = ($data->payment_method == 'non_tunai') ? 'Non Tunai' : 'Tunai';

                return $payment;
            })
            ->addColumn('payment_date', function ($data) {
                $date = \Carbon\Carbon::parse($data->payment_date)->translatedFormat('d F Y H:i');
                return $date;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';

                $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="approveOrder(' . $data->id . ')" title="Terima">Terima</button>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="rejectOrder(' . $data->id . ')" title="Tolak">Tolak</button>';

                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['user', 'total_price', 'payment_method', 'payment_date', 'action'])
            ->rawColumns(['total_price', 'action'])
            ->make(true);
    }

    public function checkout(string $id)
    {
        DB::beginTransaction();
        try {

            $user_id = Auth::user()->id;
            $package  = Package::find($id);

            $orderPackageId = OrderPackage::whereHas('order', function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->where('status', 100);
            })
                ->whereNull('deleted_at')
                ->where('class', '>', 0)
                ->where('package_id', $id)
                ->pluck('id');


            if (!is_null($package->class)) {
                if (!is_null($orderPackageId) && $orderPackageId->isNotEmpty()) {
                    $classId = ClassAttendance::where('order_package_id', $orderPackageId)->pluck('class_id');
                    $on_going_class = ClassPackage::whereIn('id', $classId)
                        ->whereColumn('current_meeting', '<', 'total_meeting')
                        ->exists();

                    if ($on_going_class) {
                        DB::rollBack();
                        session()->flash('failed', 'Kelas sedang berjalan. Selesaikan kelas Anda terlebih dahulu.');
                        return;
                    }
                }
            }

            $exist_order = Order::where('user_id',  $user_id)->where('status', 1)->first();
            if ($exist_order) {
                $duplicate_package = OrderPackage::where('order_id', $exist_order->id)
                    ->where('package_id', $id)
                    ->whereNull('deleted_at')
                    ->where('class', '>', 0)
                    ->exists();

                if ($duplicate_package) {
                    // Jika duplikat, rollback dan kirim pesan
                    DB::rollBack();
                    session()->flash('failed', 'Paket Kelas Sudah Ada, Silahkan Lihat Di MyOrder.');
                    return;
                }

                $add_order_package = OrderPackage::lockforUpdate()->create([
                    'package_id' => $id,
                    'order_id' => $exist_order->id,
                    'class' => $package->class,
                    'current_class' => 0,
                ]);
            } else {
                $new_order = Order::lockforUpdate()->create([
                    'status' => 1,
                    'user_id' => $user_id,
                ]);
                $add_order_package = OrderPackage::lockforUpdate()->create([
                    'package_id' => $id,
                    'order_id' => $new_order->id,
                    'class' => $package->class,
                    'current_class' => 0,
                ]);
            }

            if ($add_order_package) {
                DB::commit();
                session()->flash('success', 'Berhasil Checkout Paket');
            } else {
                DB::rollBack();
                session()->flash('failed', 'Gagal Checkout Paket');
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function payment(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            if ($order) {
                $update_order = Order::where('id', $id)->update([
                    'status' => 10,
                    'total_price' => (int) $request->totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now(),
                ]);

                if ($update_order) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Melakukan Pembayaran Paket');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Melakukan Pembayaran Paket');
                }
            } else {
                session()->flash('failed', 'Tidak Ada Order Yang Ditemukan');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
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
                    'status' => 100,
                ]);
                if ($approve_order) {
                    $order_package = OrderPackage::where('order_id', $id)->whereNull('deleted_at')->get();
                    $order_detail = [];
                    foreach ($order_package as $item) {
                        if ($item->package && $item->package->packageTest) {
                            foreach ($item->package->packageTest as $packageTest) {
                                $quiz = $packageTest->quiz;
                                if ($quiz) {
                                    $order_detail[] = [
                                        'order_id' => $id,
                                        'package_id' => $item->package_id,
                                        'quiz_id' => $quiz->id
                                    ];
                                }
                            }
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

            $last_order = Order::where('user_id', $order->user_id)->where('status', 1)->first();

            if ($last_order) {
                $order_package = OrderPackage::where('order_id', $id)->whereNull('deleted_at')->get();

                $get_package_in_order = [];

                foreach ($order_package as $item) {
                    $get_package_in_order[] = [
                        'package_id' => $item->package_id,
                        'class' => $item->class,
                        'current_class' => $item->current_class,
                        'order_id' => $last_order->id,
                    ];
                }
                $move_package_to_last_order = OrderPackage::insert($get_package_in_order);

                if ($move_package_to_last_order) {
                    OrderPackage::where('order_id', $id)->delete();
                    $order_deleted = $order->delete();
                    if ($order_deleted) {
                        DB::commit();
                        session()->flash('success', 'Berhasil Menolak Order');
                        return;
                    } else {
                        throw new Exception('Gagal mengubah status order.');
                    }
                } else {
                    throw new Exception('Gagal mengubah status order.');
                }
            } else {
                $reject_order = $order->update([
                    'status' => 1
                ]);
            }
            if ($reject_order) {
                DB::commit();
                session()->flash('success', 'Berhasil Menolak Order');
            } else {
                throw new Exception('Gagal mengubah status order.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('failed', $e->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $order_package = OrderPackage::find($id);

            if (!is_null($order_package)) {

                $order_cancel = OrderPackage::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($order_cancel) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Paket');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Paket');
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();
        }
    }




    // public function history(Request $request)
    // {

    //     if ($request->ajax()) {
    //         $order = Order::where('user_id', Auth::user()->id)->where(function ($query) {
    //             $query->where('status', 100)
    //                 ->orWhere('status', 10);
    //         })->whereNull('deleted_at')->get();

    //         return DataTables::of($order)
    //             ->addIndexColumn()
    //             ->addColumn('payment_date', function ($data) {
    //                 return \Carbon\Carbon::parse($data->payment_date)->translatedFormat('l, d F Y');
    //             })

    //             ->addColumn('total_price', function ($data) {
    //                 return 'Rp.' . number_format($data->total_price, 0, ',', '.');
    //             })
    //             ->addColumn('action', function ($data) {
    //                 return '<button onclick="showDetail(' . $data->id . ')" class="btn btn-sm btn-info my-1 ml-1"><i class="fas fa-eye"></i></button>';
    //             })
    //             ->only(['payment_method', 'payment_date', 'total_price', 'action'])
    //             ->rawColumns(['action', 'payment_date', 'total_price'])
    //             ->make(true);
    //     }
    //     return view('order.history');
    // }

}
