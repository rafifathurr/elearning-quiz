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

            $reject_order = $order->update([
                'status' => 1
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

    // public function dataTable()
    // {
    //     if (User::find(Auth::user()->id)->hasRole('user')) {
    //         $order = Order::whereNull('deleted_at')->where('user_id', Auth::user()->id)->get();
    //     } else {
    //         $order = Order::whereNull('deleted_at')->get();
    //     }

    //     $dataTable = DataTables::of($order)
    //         ->addIndexColumn()
    //         ->addColumn('name', function ($data) {
    //             $package_name = $data->package->name;
    //             return $package_name;
    //         })
    //         ->addColumn('total_price', function ($data) {
    //             $total_price = '<div>' . 'Rp. ' . number_format($data->total_price, 0, ',', '.');
    //             $total_price .= '</div>';
    //             return $total_price;
    //         })
    //         ->addColumn('status', function ($data) {
    //             $color = 'text-secondary';

    //             if ($data->status == 'Belum Dibayar') {
    //                 $color = 'text-warning';
    //             } elseif ($data->status == 'Menunggu Konfirmasi') {
    //                 $color = 'text-primary';
    //             } elseif ($data->status == 'Berhasil Dikonfirmasi') {
    //                 $color = 'text-success';
    //             } elseif ($data->status == 'Bukti Ditolak') {
    //                 $color = 'text-danger';
    //             }

    //             $status = '<div class="' . $color . '">' . $data->status . '</div>';
    //             return $status;
    //         })
    //         ->addColumn('class', function ($data) {
    //             return '<div>' . (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-') . '</div>';
    //         })

    //         ->addColumn('payment_method', function ($data) {
    //             $payment = ($data->payment_method == 'non_tunai') ? 'Non Tunai' : 'Tunai';

    //             return $payment;
    //         })
    //         ->addColumn('attachment', function ($data) {
    //             if (!is_null($data->attachment)) {
    //                 return '<a href="' . asset($data->attachment) . '" target= "_blank"><i class="fas fa-download mr-1"></i> Bukti Pembayaran</a>';
    //             }
    //         })
    //         ->addColumn('action', function ($data) {
    //             $btn_action = '<div align="center">';
    //             if (User::find(Auth::user()->id)->hasRole('user')) {
    //                 if (is_null($data->attachment) || $data->status == 'Bukti Ditolak') {
    //                     $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="payOrder(' . $data->id . ', \'' . addslashes($data->package->name) . '\')"  title="Bayar">Bayar</button>';
    //                     $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="cancelOrder(' . $data->id . ')" title="Batal">Batal</button>';
    //                 }
    //             } elseif (User::find(Auth::user()->id)->hasRole('admin')) {
    //                 if (!is_null($data->attachment) && $data->status == 'Menunggu Konfirmasi') {
    //                     $btn_action .= '<button class="btn btn-sm btn-success ml-2" onclick="approveOrder(' . $data->id . ')" title="Terima">Terima</button>';
    //                     $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="rejectOrder(' . $data->id . ')" title="Tolak">Tolak</button>';
    //                 } else if ($data->status == 'Berhasil Dikonfirmasi') {
    //                     $btn_action .= '<span class="text-success ml-2">Bukti Diterima</span>';
    //                 } else if ($data->status == 'Bukti Ditolak') {
    //                     $btn_action .= '<span class="text-danger ml-2">Bukti Ditolak</span>';
    //                 } else {
    //                     $btn_action .= '<span class="text-warning ml-2">Belum Dibayar</span>';
    //                 }
    //             }
    //             $btn_action .= '</div>';
    //             return $btn_action;
    //         })

    //         ->only(['name', 'total_price', 'class', 'status', 'payment_method', 'attachment', 'action'])
    //         ->rawColumns(['action', 'total_price', 'status', 'class', 'attachment'])
    //         ->make(true);

    //     return $dataTable;
    // }

    // public function checkout(Request $request, string $id)
    // {
    //     DB::beginTransaction();
    //     try {

    //         $package  = Package::find($id);
    //         $add_order = Order::lockforUpdate()->create([
    //             'package_id' => $id,
    //             'total_price' => $package->price,
    //             'class' => $package->class,
    //             'payment_method' => $request->payment_method,
    //             'user_id' => Auth::user()->id,
    //             'status' => 'Belum Dibayar',

    //         ]);


    //         if ($add_order) {
    //             DB::commit();
    //             session()->flash('success', 'Berhasil Checkout Paket');
    //         } else {
    //             DB::rollBack();
    //             session()->flash('failed', 'Gagal Checkout Paket');
    //         }
    //     } catch (Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with(['failed' => $e->getMessage()]);
    //     }
    // }

    // public function payment(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::findOrFail($id);
    //         if ($order) {

    //             $request->validate([
    //                 'upload_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             ]);
    //             if ($request->hasFile('upload_image')) {

    //                 $path = 'public/order/images' .  $id;
    //                 $path_store = 'storage/order/images' .  $id;

    //                 if (!Storage::exists($path)) {
    //                     Storage::makeDirectory($path);
    //                 }

    //                 $file_name = $id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('upload_image')->getClientOriginalExtension();

    //                 if (Storage::exists($path . '/' . $file_name)) {
    //                     Storage::delete($path . '/' . $file_name);
    //                 }
    //                 $request->file('upload_image')->storePubliclyAs($path, $file_name);

    //                 $attachment = $path_store . '/' . $file_name;

    //                 $order->update([
    //                     'payment_date' => now(),
    //                     'status' => 'Menunggu Konfirmasi',
    //                     'attachment' => $attachment,
    //                 ]);
    //                 DB::commit();
    //                 session()->flash('success', 'Berhasil Upload Bukti Pembayaran');
    //             } else {
    //                 DB::rollBack();
    //                 session()->flash('failed', 'Gagal Upload Bukti Pembayaran');
    //             }
    //         } else {
    //             return redirect()
    //                 ->back()
    //                 ->with(['failed' => 'Data Order Tidak Ditemukan']);
    //         }
    //     } catch (Exception $e) {
    //         session()->flash('failed', $e->getMessage());
    //     }
    // }
}
