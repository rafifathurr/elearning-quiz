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
use Illuminate\Support\Facades\File;

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

            ->addColumn('date', function ($data) {
                return $data->dateClass ? $data->dateClass->name : '-';
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
            ->addColumn('proof_payment', function ($data) {
                if (!is_null($data->proof_payment)) {
                    return '<a href="' . asset($data->proof_payment) . '" target= "_blank"><i class="fas fa-download mr-1"></i> Bukti Pembayaran</a>';
                }
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
            ->only(['user', 'total_price', 'payment_method', 'proof_payment', 'payment_date', 'action'])
            ->rawColumns(['total_price', 'proof_payment', 'action'])
            ->make(true);
    }

    public function getSchedule($id)
    {
        $package = Package::with('packageDate.dateClass')->find($id);

        $schedules = [];
        if ($package && $package->packageDate->isNotEmpty()) {
            foreach ($package->packageDate as $packageDate) {
                $schedules[] = [
                    'id' => $packageDate->dateClass->id,
                    'name' => $packageDate->dateClass->name
                ];
            }
        }

        return response()->json(['schedules' => $schedules]);
    }


    public function checkout(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

            $user_id = Auth::user()->id;
            $package  = Package::find($id);
            $schedule_id = $request->input('schedule_id');
            $schedule_id = (!empty($schedule_id) && $schedule_id != '0') ? intval($schedule_id) : null;

            Log::info('schedule id: ' . $schedule_id);




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
                    'date_class_id' => $schedule_id
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
                    'date_class_id' => $schedule_id
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
            $request->validate([
                'proof_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $order = Order::find($id);
            if ($order) {
                if ($request->hasFile('proof_payment')) {
                    $path = 'public/order/proof' .  $id;
                    $path_store = 'storage/order/proof' .  $id;

                    if (Storage::exists($path)) {
                        Storage::deleteDirectory($path);
                    }
                    Storage::makeDirectory($path);
                    // Ubah izin folder menjadi 775 setelah membuat folder
                    $folderPath = storage_path('app/' . $path);
                    File::chmod($folderPath, 0775);  // Set izin folder menjadi 775

                    $file_name = $id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('proof_payment')->getClientOriginalExtension();

                    $request->file('proof_payment')->storePubliclyAs($path, $file_name);

                    $attachment = $path_store . '/' . $file_name;

                    $update_order = Order::where('id', $id)->update([
                        'status' => 10,
                        'total_price' => (int) $request->totalPrice,
                        'payment_method' => 'non_tunai',
                        'payment_date' => now(),
                        'proof_payment' => $attachment
                    ]);

                    if ($update_order) {
                        DB::commit();
                        session()->flash('success', 'Berhasil Melakukan Pembayaran Paket');
                    } else {
                        DB::rollBack();
                        session()->flash('failed', 'Gagal Melakukan Pembayaran Paket');
                    }
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Upload Bukti Pembayaran');
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
                        if ($item->package) {
                            if ($item->package->packageTest->isNotEmpty()) {
                                foreach ($item->package->packageTest as $packageTest) {
                                    $order_detail[] = [
                                        'order_id' => $id,
                                        'package_id' => $item->package_id,
                                        'quiz_id' => $packageTest->quiz->id ?? null
                                    ];
                                }
                            } else {
                                $order_detail[] = [
                                    'order_id' => $id,
                                    'package_id' => $item->package_id,
                                    'quiz_id' => null
                                ];
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

    // public function payment(Request $request, string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::find($id);
    //         if ($order) {
    //             $update_order = Order::where('id', $id)->update([
    //                 'status' => 10,
    //                 'total_price' => (int) $request->totalPrice,
    //                 'payment_method' => $request->payment_method,
    //                 'payment_date' => now(),
    //             ]);

    //             if ($update_order) {
    //                 DB::commit();
    //                 session()->flash('success', 'Berhasil Melakukan Pembayaran Paket');
    //             } else {
    //                 DB::rollBack();
    //                 session()->flash('failed', 'Gagal Melakukan Pembayaran Paket');
    //             }
    //         } else {
    //             session()->flash('failed', 'Tidak Ada Order Yang Ditemukan');
    //         }
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         session()->flash('failed', $e->getMessage());
    //     }
    // }

    // Ada select untuk jadwal kelas
    // public function dataTable()
    // {
    //     $order_ids = Order::whereNull('deleted_at')
    //         ->where('user_id', Auth::user()->id)
    //         ->where('status', 1)
    //         ->pluck('id');

    //     $order_package = OrderPackage::whereNull('deleted_at')
    //         ->whereIn('order_id', $order_ids)
    //         ->get();

    //     $totalPrice = $order_package->sum(function ($data) {
    //         return $data->package->price;
    //     });

    //     $order_id = null;
    //     if ($order_package->isNotEmpty()) {
    //         $order_id = $order_package->first()->order_id;
    //     }

    //     return DataTables::of($order_package)
    //         ->addIndexColumn()
    //         ->addColumn('name', function ($data) {
    //             return $data->package->name;
    //         })
    //         ->addColumn('class', function ($data) {
    //             return (!is_null($data->class) && $data->class > 0 ? $data->class . 'x Pertemuan' : '-');
    //         })
    //         ->addColumn('price', function ($data) {
    //             return 'Rp. ' . number_format($data->package->price, 0, ',', '.');
    //         })

    //         ->addColumn('date', function ($data) {
    //             if ($data->package->packageDate->isNotEmpty()) {
    //                 $select = '<select class="form-control" name="date_class_id_' . $data->id . '" required>';
    //                 $select .= '<option disabled hidden selected>Pilih Jadwal</option>';

    //                 foreach ($data->package->packageDate as $packageDate) {
    //                     $select .= '<option value="' . $packageDate->dateClass->id . '">' . $packageDate->dateClass->name . '</option>';
    //                 }

    //                 $select .= '</select>';
    //                 return $select;
    //             } else {
    //                 // Jika tidak ada packageDate, tampilkan tanda "-"
    //                 return '-';
    //             }
    //         })

    //         ->addColumn('action', function ($data) {
    //             return '<div align="center">
    //                         <button class="btn btn-sm btn-danger ml-2" onclick="cancelOrder(' . $data->id . ')" title="Hapus">Hapus</button>
    //                     </div>';
    //         })
    //         ->addColumn('order_id', function () use ($order_id) {
    //             return $order_id ? $order_id : '-';
    //         })

    //         ->with('totalPrice', 'Rp. ' . number_format($totalPrice, 0, ',', '.'))
    //         ->rawColumns(['price', 'date', 'action'])
    //         ->make(true);
    // }



    public function history(Request $request)
    {

        if ($request->ajax()) {
            $order = Order::where('user_id', Auth::user()->id)->where(function ($query) {
                $query->where('status', 100)
                    ->orWhere('status', 10)
                    ->orWhere('status', 1)->whereNotNull('proof_payment');
            })->whereNull('deleted_at')->get();

            return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('payment_date', function ($data) {
                    return \Carbon\Carbon::parse($data->payment_date)->translatedFormat('l, d F Y');
                })
                ->addColumn('status_payment', function ($data) {
                    $list_view = '<div align="center">';
                    if ($data->status == 100) {
                        $list_view .= '<span class="badge bg-success p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Berhasil</span>';
                    } elseif ($data->status == 10) {
                        $list_view .= '<span class="badge bg-warning text-dark p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Menunggu Konfirmasi</span>';
                    } else {
                        $list_view .= '<span class="badge bg-danger p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Ditolak</span>';
                    }
                    $list_view .= '</div>';
                    return $list_view;
                })

                ->addColumn('total_price', function ($data) {
                    return 'Rp.' . number_format($data->total_price, 0, ',', '.');
                })
                ->addColumn('order_id', function ($data) {
                    $year = \Carbon\Carbon::parse($data->created_at)->format('y'); // Ambil 2 digit terakhir tahun
                    return 'BC' . $year . $data->id;
                })
                ->addColumn('order_package', function ($data) {
                    $list_view = '<div align="center">';
                    foreach ($data->orderPackages->whereNull('deleted_at') as $order) {
                        $list_view .= '<span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">' . $order->package->name . '</span>';
                    };
                    $list_view .= '</div>';
                    return $list_view;
                })
                ->only(['status_payment', 'order_id', 'payment_date', 'total_price', 'order_package'])
                ->rawColumns(['payment_date', 'status_payment', 'total_price', 'order_package'])
                ->make(true);
        }
        return view('order.history');
    }
}
