<?php

namespace App\Http\Controllers;

use App\Mail\ApproveOrderMail;
use App\Mail\InvoiceMail;
use App\Mail\NewOrderMail;
use App\Mail\RejectOrderMail;
use App\Models\ClassAttendance;
use App\Models\ClassPackage;
use App\Models\ClassUser;
use App\Models\DateClass;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\OrderVoucher;
use App\Models\Package;
use App\Models\SupportBriva;
use App\Models\TokenData;
use App\Models\User;
use App\Models\Voucher;
use Exception;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index()
    {
        $datatable_route = route('order.dataTable');
        $orderFor = Order::whereNull('deleted_at')
            ->where('order_by', Auth::user()->id)
            ->where('status', 1)
            ->first();
        return view('order.index', compact('datatable_route', 'orderFor'));
    }

    public function dataTable()
    {
        if (User::find(Auth::user()->id)->hasRole('user') && !User::find(Auth::user()->id)->hasRole('counselor')) {
            $order_ids = Order::whereNull('deleted_at')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->pluck('id');
        } elseif (User::find(Auth::user()->id)->hasAllRoles(['counselor', 'user'])) {
            $order_ids = Order::whereNull('deleted_at')
                ->where('order_by', Auth::user()->id)
                ->where('status', 1)
                ->pluck('id');
        } else {
            $order_ids = Order::whereNull('deleted_at')
                ->where('order_by', Auth::user()->id)
                ->where('status', 1)
                ->pluck('id');
        }


        $order_package = OrderPackage::whereNull('deleted_at')
            ->whereIn('order_id', $order_ids)
            ->get();

        $totalPrice = $order_package->sum(function ($data) {
            return $data->price ?? $data->package->price;
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
                $price = $data->price ?? $data->package->price;
                return 'Rp. ' . number_format($price, 0, ',', '.');
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

    public function listOrder()
    {
        $orderQuery = Order::whereNull('deleted_at')
            ->whereIn('payment_method', ['transfer', 'briva']);

        $data['all_order'] = Order::whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNull('payment_method')
                    ->orWhereIn('payment_method', ['transfer', 'briva']);
            })->count();
        $data['check_out'] = Order::whereNull('deleted_at')->whereNull('payment_method')->where('status', 1)->count();
        $data['not_payment'] = (clone $orderQuery)->where('status', 2)->whereNull('proof_payment')->count();
        $data['order_reject'] = (clone $orderQuery)->where('status', 2)->whereNotNull('proof_payment')->count();
        $data['success_order'] = (clone $orderQuery)->where('status', 100)->count();
        $data['total_revenue'] = (clone $orderQuery)->where('status', 100)->sum('total_price');
        $data['payment_method_count'] = Order::whereNull('deleted_at')
            ->whereIn('payment_method', ['transfer', 'briva'])
            ->where('status', 100)
            ->selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();
        $data['datatable_route'] = route('order.dataTableListOrder');

        return view('order.list-order', $data);
    }


    public function dataTableListOrder()
    {
        $statusFilter = request()->get('status');
        $data = Order::query()
            ->where(function ($query) {
                $query->where('status', 100)
                    ->orWhere('status', 10)
                    ->orWhere('status', 2)->whereNotNull('proof_payment');
            })->whereNull('deleted_at');

        // Filter berdasarkan paket jika dipilih
        if ($statusFilter) {
            $data->where('status', $statusFilter);
        }

        if (!$statusFilter) {
            $data->orderByDesc('payment_date');
        }
        $order = $data->get();

        return DataTables::of($order)
            ->addIndexColumn()
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->addColumn('total_price', function ($data) {
                return 'Rp. ' . number_format($data->total_price, 0, ',', '.');
            })
            ->addColumn('updated_at', function ($data) {
                return \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y');
            })
            ->addColumn('proof_payment', function ($data) {
                if (!is_null($data->proof_payment)) {

                    //Download Gambar
                    return '<a href="' . route('order.downloadPayment', $data->id) . '" target="_blank"><i class="fas fa-download mr-1"></i> Bukti Pembayaran</a>';

                    // Tab baru bukan download
                    return '<a href="' . route('order.downloadPayment', $data->id) . '" target="_blank"><i class="fas fa-eye mr-1"></i> Lihat Bukti</a>';
                }
            })
            ->addColumn('order_id', function ($data) {
                $year = \Carbon\Carbon::parse($data->created_at)->format('y');
                return 'BC' . $year . $data->id;
            })
            ->addColumn('status_payment', function ($data) {
                $list_view = '<div align="center">';
                if ($data->status == 100) {
                    $list_view .= '<span class="badge bg-success p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Berhasil</span>';
                } elseif ($data->status == 10) {
                    $list_view .= '<span class="badge bg-warning  p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Menunggu Konfirmasi</span>';
                } else {
                    $list_view .= '<span class="badge bg-danger p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Ditolak</span>';
                }
                $list_view .= '</div>';
                return $list_view;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('order.detailOrder', ['id' => $data->id]) . '"  class="btn btn-sm btn-primary ml-2" >Detail</a>';

                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['user', 'total_price', 'payment_method', 'order_id', 'action', 'status_payment', 'updated_at'])
            ->rawColumns(['total_price', 'action', 'status_payment', 'updated_at'])
            ->make(true);
    }

    function detailOrder(string $id)
    {
        try {
            $order = Order::find($id);

            $allow_role = User::find(Auth::user()->id)->hasAnyRole('admin', 'finance', 'manager');

            if (!$allow_role) {
                return redirect()
                    ->back()
                    ->with('failed', 'Anda Tidak Bisa Akses Halaman Ini!');
            }

            $order_package = OrderPackage::whereNull('deleted_at')
                ->where('order_id', $id)
                ->get();

            $order_voucher = OrderVoucher::whereNull('deleted_at')
                ->where('order_id', $id)
                ->get();

            $totalPrice = $order_package->sum(function ($data) {
                return $data->package->price;
            });

            return view('order.detail-order', compact('order', 'order_package', 'order_voucher', 'totalPrice'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
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

    public function getUser()
    {
        $users = User::whereNull('deleted_at')->where('status', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        return response()->json(['users' => $users]);
    }


    public function checkout(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

            $user_id = Auth::user()->id;
            $package  = Package::find($id);
            $schedule_id = $request->input('schedule_id');
            $schedule_id = (!empty($schedule_id) && $schedule_id != '0') ? intval($schedule_id) : null;
            $kode_voucher = trim($request->input('kode_voucher'));

            $date_class = null;
            if (!is_null($schedule_id)) {
                $date_class = DateClass::find($schedule_id);
            }

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
                    // Cek apakah order_package_id sudah ada di ClassUser
                    $isNotInClassUser = ClassUser::whereIn('order_package_id', $orderPackageId)->doesntExist();

                    // Cek apakah class sedang berjalan (current_meeting < total_meeting)
                    $classId = ClassUser::whereIn('order_package_id', $orderPackageId)->pluck('class_id');
                    $on_going_class = ClassPackage::whereIn('id', $classId)
                        ->whereColumn('current_meeting', '<', 'total_meeting')
                        ->exists();

                    // Jika order_package belum ada di ClassUser ATAU sedang berjalan, tampilkan alert
                    if ($isNotInClassUser || $on_going_class) {
                        DB::rollBack();
                        session()->flash('failed', 'Kelas sedang berjalan atau belum dimulai.');
                        return;
                    }
                }
            }


            $exist_order = Order::where('user_id',  $user_id)->where('status', 1)->first();
            $voucher = null;
            $discount_amount = 0;

            //Kalau ada Voucher
            if (!empty($kode_voucher)) {
                $voucher = OrderVoucher::where('voucher_code', $kode_voucher)
                    ->where('status', 1)
                    ->whereHas('order', function ($q) {
                        $q->where('status', 100); // hanya order yang sudah approved
                    })
                    ->first();

                if (!$voucher) {
                    DB::rollBack();
                    session()->flash('failed', 'Kode voucher tidak valid atau sudah digunakan.');
                    return;
                }

                if ($voucher->package_id != $package->id) {
                    DB::rollBack();
                    session()->flash('failed', 'Kode voucher tidak berlaku untuk paket ini.');
                    return;
                }

                // Hitung potongan harga
                if ($voucher->type_voucher == 'discount') {
                    $discount_amount = ($package->price * $voucher->voucher_value) / 100;
                } elseif ($voucher->type_voucher == 'fixed_price') {
                    $discount_amount = $voucher->voucher_value;
                }
            }

            $final_price = max(0, $package->price - $discount_amount); // Hindari minus

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
                    'date_class_id' => $schedule_id,
                    'date_in_class' => !empty($date_class) ? $date_class->name : null,
                    'price' => $final_price,
                    'voucher_code' => $voucher->voucher_code ?? null,
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
                    'date_class_id' => $schedule_id,
                    'date_in_class' => !empty($date_class) ? $date_class->name : null,
                    'price' => $final_price,
                    'voucher_code' => $voucher->voucher_code ?? null,

                ]);
            }

            // Update status voucher menjadi "sudah digunakan" jika berhasil
            if ($voucher) {
                $voucher->update(['status' => 10]);
            }

            if ($add_order_package) {
                DB::commit();
                session()->flash('berhasil', 'Berhasil Ambil Paket');
            } else {
                DB::rollBack();
                session()->flash('failed', 'Gagal Ambil Paket');
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function checkoutCounselor(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

            $counselor_id = Auth::user()->id;
            $package  = Package::find($id);
            $schedule_id = $request->input('schedule_id');
            $user_id = $request->input('user_id');
            $schedule_id = (!empty($schedule_id) && $schedule_id != '0') ? intval($schedule_id) : null;
            $user_id = (!empty($user_id) && $user_id != '0') ? intval($user_id) : null;
            $kode_voucher = trim($request->input('kode_voucher'));

            $date_class = null;
            if (!is_null($schedule_id)) {
                $date_class = DateClass::find($schedule_id);
            }

            Log::info('schedule id: ' . $schedule_id);
            Log::info('User id: ' . $user_id);

            $onGoingOrder = Order::where('order_by',  $counselor_id)->where('status', 1)->first();
            if ($onGoingOrder) {
                if ($user_id != $onGoingOrder->user_id) {
                    DB::rollBack();
                    session()->flash('failed', 'Silahkan Bayar Pesanan Dahulu Sebelum Berganti User.');
                    return;
                }
            }

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
                    // Cek apakah order_package_id sudah ada di ClassUser
                    $isNotInClassUser = ClassUser::whereIn('order_package_id', $orderPackageId)->doesntExist();

                    // Cek apakah class sedang berjalan (current_meeting < total_meeting)
                    $classId = ClassUser::whereIn('order_package_id', $orderPackageId)->pluck('class_id');
                    $on_going_class = ClassPackage::whereIn('id', $classId)
                        ->whereColumn('current_meeting', '<', 'total_meeting')
                        ->exists();

                    // Jika order_package belum ada di ClassUser ATAU sedang berjalan, tampilkan alert
                    if ($isNotInClassUser || $on_going_class) {
                        DB::rollBack();
                        session()->flash('failed', 'Kelas sedang berjalan atau belum dimulai.');
                        return;
                    }
                }
            }


            $order_with_other_couns = Order::where('user_id',  $user_id)
                ->where('order_by', '!=', $counselor_id)
                ->where('status', 1)->first();
            if ($order_with_other_couns) {
                DB::rollBack();
                session()->flash('failed', 'Peserta ini sudah dipesankan oleh konselor lain');
                return;
            }

            $exist_order = Order::where('user_id',  $user_id)->where(function ($query) {
                $query->whereNull('order_by')
                    ->orWhere('order_by', Auth::user()->id);
            })
                ->where('status', 1)->first();

            $voucher = null;
            $discount_amount = 0;

            //Kalau ada Voucher
            if (!empty($kode_voucher)) {
                $voucher = OrderVoucher::where('voucher_code', $kode_voucher)
                    ->where('status', 1)
                    ->whereHas('order', function ($q) {
                        $q->where('status', 100); // hanya order yang sudah approved
                    })
                    ->first();

                if (!$voucher) {
                    DB::rollBack();
                    session()->flash('failed', 'Kode voucher tidak valid atau sudah digunakan.');
                    return;
                }

                if ($voucher->package_id != $package->id) {
                    DB::rollBack();
                    session()->flash('failed', 'Kode voucher tidak berlaku untuk paket ini.');
                    return;
                }

                // Hitung potongan harga
                if ($voucher->type_voucher == 'discount') {
                    $discount_amount = ($package->price * $voucher->voucher_value) / 100;
                } elseif ($voucher->type_voucher == 'fixed_price') {
                    $discount_amount = $voucher->voucher_value;
                }
            }

            $final_price = max(0, $package->price - $discount_amount); // Hindari minus

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
                // Update order_by kalau order_by nya null (awalnya di co oleh user)
                if ($exist_order->order_by == null) {
                    $exist_order->update([
                        'order_by' => $counselor_id
                    ]);
                }
                $add_order_package = OrderPackage::lockforUpdate()->create([
                    'package_id' => $id,
                    'order_id' => $exist_order->id,
                    'class' => $package->class,
                    'current_class' => 0,
                    'date_class_id' => $schedule_id,
                    'date_in_class' => !empty($date_class) ? $date_class->name : null,
                    'price' => $final_price,
                    'voucher_code' => $voucher->voucher_code ?? null,
                ]);
            } else {
                $new_order = Order::lockforUpdate()->create([
                    'status' => 1,
                    'user_id' => $user_id,
                    'order_by' => $counselor_id,
                ]);
                $add_order_package = OrderPackage::lockforUpdate()->create([
                    'package_id' => $id,
                    'order_id' => $new_order->id,
                    'class' => $package->class,
                    'current_class' => 0,
                    'date_class_id' => $schedule_id,
                    'date_in_class' => !empty($date_class) ? $date_class->name : null,
                    'price' => $final_price,
                    'voucher_code' => $voucher->voucher_code ?? null,
                ]);
            }

            // Update status voucher menjadi "sudah digunakan" jika berhasil
            if ($voucher) {
                $voucher->update(['status' => 10]);
            }
            if ($add_order_package) {
                DB::commit();
                session()->flash('berhasil', 'Berhasil Ambil Paket');
            } else {
                DB::rollBack();
                session()->flash('failed', 'Gagal Ambil Paket');
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
            $order_package = OrderPackage::whereNull('deleted_at')
                ->where('order_id', $id)
                ->get();
            if ($order) {
                $totalPrice =  (int) $request->totalPrice;
                $update_order = Order::where('id', $id)->update([
                    'status' => 2,
                    'total_price' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now()
                ]);

                if ($update_order) {
                    DB::commit();

                    // Jika metode pembayaran transfer, arahkan ke detailPayment
                    if ($request->payment_method == 'transfer') {
                        if (User::find(Auth::user()->id)->hasRole('user') && !User::find(Auth::user()->id)->hasRole('counselor')) {
                            $sendMail = Mail::to(Auth::user()->email)->send(new InvoiceMail($order, $order_package, $totalPrice));
                        } else {
                            $sendMail = Mail::to($order->user->email)->send(new InvoiceMail($order, $order_package, $totalPrice));
                        }
                        if ($sendMail) {
                            return response()->json([
                                'success' => true,
                                'redirect_url' => route('order.detailPayment', ['id' => $id])
                            ]);
                        }
                    } elseif ($request->payment_method == 'briva') {
                        $year = now()->format('y'); // Ambil 2 digit terakhir tahun
                        // Gabungkan tahun dan order ID menjadi satu angka
                        $digitOrderId = $year . $id;

                        // Cek apakah panjangnya sudah 13 digit, jika belum tambahkan 0 di depan
                        $customerNo = str_pad($digitOrderId, 13, '0', STR_PAD_LEFT);
                        // Tambahkan prefix "19114" di depan VA
                        $prefixVa = '19114' . $customerNo;

                        $insert_briva = SupportBriva::create([
                            'order_id' => $id,
                            'customer_no' => $customerNo,
                            'va' => $prefixVa,
                            'source' => 'BRI',
                            'latest_inquiry' => null,
                            'create_time' => now(),
                            'payment_time' => null,
                        ]);

                        if ($insert_briva) {
                            return response()->json([
                                'success' => true,
                                'redirect_url' => route('order.detailPayment', ['id' => $id])
                            ]);
                        }
                    }


                    // Jika bukan transfer atau briva, arahkan ke home atau halaman sukses lainnya
                    return response()->json([
                        'success' => true,
                        'redirect_url' => route('home')
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Gagal Melakukan Pembayaran Paket'], 500);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Tidak Ada Order Yang Ditemukan'], 404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    function encodeAlpha($number)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $result = '';

        while ($number > 0) {
            $result = $characters[$number % $base] . $result;
            $number = intdiv($number, $base);
        }

        return $result;
    }


    public function checkoutVoucher(Request $request, String $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'voucher_id' => 'required|exists:vouchers,id',
                'jumlah' => 'required|integer|min:1',
                'metode' => 'required|in:transfer,briva',
                'harga' => 'required|numeric|min:0',
            ]);

            $totalPrice = $request->jumlah * $request->harga;

            $order = Order::create([
                'status' => 2,
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'payment_method' => $request->metode,
                'payment_date' => now()
            ]);

            $voucher = Voucher::find($request->voucher_id);
            $userId = Auth::id();
            $timestamp = now()->timestamp;

            for ($i = 0; $i < $request->jumlah; $i++) {
                do {
                    $raw = (int)($order->id . $userId . $request->voucher_id . $timestamp . $i);
                    $alphaCode = $this->encodeAlpha($raw);
                    $kodeVoucher = 'BC-' . str_pad($alphaCode, 12, 'X', STR_PAD_RIGHT);
                } while (OrderVoucher::where('voucher_code', $kodeVoucher)->exists());

                OrderVoucher::create([
                    'order_id' => $order->id,
                    'voucher_id' => $request->voucher_id,
                    'package_id' => $voucher->package_id,
                    'price' => $request->harga,
                    'status' => 1,
                    'voucher_code' => $kodeVoucher,
                    'type_voucher' => $voucher->type_voucher,
                    'voucher_value' => $voucher->type_voucher === 'discount'
                        ? $voucher->discount
                        : $voucher->fixed_price,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('order.detailPayment', ['id' => $order->id])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function uploadPayment(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'proof_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $order = Order::find($id);
            if ($order) {
                if ($request->hasFile('proof_payment')) {
                    $path = 'private/order/proof' .  $id;
                    $path_store = 'order/proof' .  $id;

                    if (Storage::exists($path)) {
                        Storage::deleteDirectory($path);
                    }
                    Storage::makeDirectory($path);
                    // Ubah izin folder menjadi 775 setelah membuat folder
                    $folderPath = storage_path('app/' . $path);
                    File::chmod($folderPath, 0775);  // Set izin folder menjadi 775

                    $file_name = $id . '-' . uniqid() . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('proof_payment')->getClientOriginalExtension();

                    $request->file('proof_payment')->storeAs($path, $file_name);

                    $attachment = $path_store . '/' . $file_name;

                    $update_order = Order::where('id', $id)->update([
                        'status' => 10,
                        'rekening_number' => '038501001542300 (ATLAS KAPITAL PERKASA)',
                        'proof_payment' => $attachment,
                        'payment_date' => now(),
                    ]);

                    if ($update_order) {
                        $this->sendFcmNotificationToAllDevices("Ada Order Baru!", "Silakan cek sistem untuk melihat detailnya.");
                        DB::commit();
                        return redirect()
                            ->route('order.history')
                            ->with(['success' => 'Berhasil Upload Bukti Pembayaran']);
                    } else {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Bukti Pembayaran']);
                    }
                } else {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Upload Bukti Pembayaran']);
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Upload Bukti Pembayaran']);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    private function sendFcmNotificationToAllDevices($title, $body)
    {
        $tokens = TokenData::whereHas('user.roles', function ($query) {
            $query->where('name', 'admin');
        })->with('user')->get();

        $accessToken = $this->getGoogleAccessToken();

        foreach ($tokens as $tokenData) {
            $token = $tokenData->token;
            $user = $tokenData->user;

            try {
                $response = Http::withToken($accessToken)->post("https://fcm.googleapis.com/v1/projects/brata-cerdas-1/messages:send", [
                    'message' => [
                        'token' => $token,
                        'data' => [
                            'title' => $title,
                            'body' => $body
                        ]
                    ]
                ]);

                // Logging
                Log::info('FCM Notification sent', [
                    'user_id' => $user->id ?? null,
                    'name' => $user->name ?? null,
                    'email' => $user->email ?? null,
                    'token' => $token,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            } catch (\Exception $e) {
                // Logging error
                Log::error('Failed to send FCM notification', [
                    'user_id' => $user->id ?? null,
                    'name' => $user->name ?? null,
                    'token' => $token,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }


    private function getGoogleAccessToken()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/firebase/firebase-credentials.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        return $client->getAccessToken()['access_token'];
    }



    public function downloadProof($id)
    {
        $order = Order::find($id);

        if (!$order || !$order->proof_payment) {
            abort(404);
        }

        // Cek apakah user memiliki izin untuk melihat bukti pembayaran ini
        if (!User::find(Auth::user()->id)->hasAnyRole('admin', 'finance', 'manager')) {
            abort(403);
        }


        $path = storage_path('app/private/' . $order->proof_payment);

        if (!file_exists($path)) {
            abort(404);
        }

        // download foto
        // return response()->download($path);

        // Tab baru bukan download
        return response()->file($path);
    }

    public function viewPayment($id)
    {
        $order = Order::find($id);

        if (!$order || !$order->proof_payment) {
            abort(404);
        }

        // Cek apakah user memiliki akses ke order ini
        if (Auth::user()->id != $order->user_id && Auth::user()->id != $order->order_by) {
            abort(403);
        }

        $path = 'private/' . $order->proof_payment;

        if (!Storage::exists($path)) {
            abort(404);
        }

        // Mendapatkan konten file
        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        // Mengembalikan gambar dengan response manual
        return new Response($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $order->proof_payment . '"'
        ]);
    }


    public function approve(string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);

            // Update status order ke approved
            $approve_order = $order->update([
                'status' => 100,
                'approval_date' => now(),
                'approval_by' => Auth::user()->id,
            ]);

            if (!$approve_order) {
                DB::rollBack();
                session()->flash('failed', 'Gagal Menerima Order');
                return;
            }

            // Ambil data order package (jika ada)
            $order_package = OrderPackage::where('order_id', $id)->whereNull('deleted_at')->get();

            if ($order_package->isNotEmpty()) {
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

                // Simpan detail jika ada
                if (!empty($order_detail)) {
                    OrderDetail::insert($order_detail);
                }
            }

            // Kirim email tetap dikirim, baik ada paket atau tidak
            Mail::to($order->user->email)->send(new ApproveOrderMail($order, $order_package));

            DB::commit();
            session()->flash('success', 'Berhasil Menerima Order');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('failed', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function reject(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);

            $reject_order = $order->update([
                'status' => 2,
                'reason' => $request->reason,
            ]);

            if ($reject_order) {
                Mail::to($order->user->email)->send(new RejectOrderMail($order));
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

                // Ambil voucher jika ada
                $voucher = null;
                if (!empty($order_package->voucher_code)) {
                    $voucher = OrderVoucher::where('voucher_code', $order_package->voucher_code)->first();
                }

                $order_cancel = OrderPackage::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'voucher_code' => null,
                ]);

                // Cek apakah masih ada paket aktif di order yang sama
                $exists_order_package = OrderPackage::where('order_id', $order_package->order_id)
                    ->whereNull('deleted_at')
                    ->exists();

                // Jika tidak ada OrderPackage yang aktif, hapus Order
                if (!$exists_order_package) {
                    OrderPackage::where('order_id', $order_package->order_id)->delete();
                    Order::where('id', $order_package->order_id)->delete();
                }

                if ($order_cancel) {
                    if ($voucher) {
                        $voucher->update(['status' => 1]);
                    }
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

    public function history(Request $request)
    {

        if ($request->ajax()) {

            $order = Order::where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhere('order_by', Auth::user()->id);
            })->where(function ($query) {
                $query->where('status', 100)
                    ->orWhere('status', 10)
                    ->orWhere('status', 2)
                    ->orWhere('status', 1)->whereNotNull('proof_payment');
            })->whereNull('deleted_at')
                ->orderByDesc('created_at')
                ->get();

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
                        $list_view .= '<span class="badge bg-maroon  p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Menunggu Konfirmasi</span>';
                    } elseif ($data->status == 2 && !is_null($data->proof_payment)) {
                        $list_view .= '<span class="badge bg-danger p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Ditolak</span>';
                    } else {
                        $list_view .= '<span class="badge bg-warning text-dark p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Menunggu Pembayaran</span>';
                    }
                    $list_view .= '</div>';
                    return $list_view;
                })
                ->addColumn('payment_method', function ($data) {
                    $list_view = '<div align="center">';
                    if ($data->payment_method == 'transfer') {
                        $list_view .= '<span class="badge bg-maroon p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">Transfer</span>';
                    } else {
                        $list_view .= '<span class="badge bg-lightblue p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">BRIVA</span>';
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
                ->addColumn('action', function ($data) {
                    // if ($data->status == 2) {
                    $btn_action = '<div align="center">';
                    $btn_action .= '<a href="' . route('order.detailPayment', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail">Detail</a>';
                    $btn_action .= '</div>';
                    return $btn_action;
                    // } else {
                    //     return null;
                    // }
                })
                ->only(['status_payment', 'payment_method', 'order_id', 'payment_date', 'total_price', 'action'])
                ->rawColumns(['payment_date', 'payment_method', 'status_payment', 'total_price', 'action'])
                ->setRowClass(function ($data) {
                    return (!is_null($data->order_by) && $data->order_by != Auth::user()->id) ||
                        ($data->user_id != Auth::user()->id) ? 'custom-background' : '';
                })

                ->make(true);
        }
        return view('order.history');
    }

    function detailPayment(string $id)
    {
        try {
            $order = Order::find($id);

            if ($order->user_id != Auth::user()->id && $order->order_by != Auth::user()->id) {
                return redirect()
                    ->back()
                    ->with('failed', 'Anda Tidak Bisa Akses Halaman Ini!');
            }
            $order_package = OrderPackage::whereNull('deleted_at')
                ->where('order_id', $id)
                ->get();

            $order_voucher = OrderVoucher::whereNull('deleted_at')
                ->where('order_id', $id)
                ->get();

            $totalPrice = $order_package->sum(function ($data) {
                return $data->package->price;
            });

            return view('order.detail-payment', compact('order', 'order_package', 'order_voucher', 'totalPrice'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }

    // Reject kondisi kalau ada order di my order data yang di reject pindah ke order baru
    // order yang direject dihapus
    // public function reject(string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::findOrFail($id);

    //         $last_order = Order::where('user_id', $order->user_id)->where('status', 1)->first();

    //         if ($last_order) {
    //             $order_package = OrderPackage::where('order_id', $id)->whereNull('deleted_at')->get();

    //             $get_package_in_order = [];

    //             foreach ($order_package as $item) {
    //                 $get_package_in_order[] = [
    //                     'package_id' => $item->package_id,
    //                     'class' => $item->class,
    //                     'current_class' => $item->current_class,
    //                     'order_id' => $last_order->id,
    //                 ];
    //             }
    //             $move_package_to_last_order = OrderPackage::insert($get_package_in_order);

    //             if ($move_package_to_last_order) {
    //                 OrderPackage::where('order_id', $id)->delete();
    //                 $order_deleted = $order->delete();
    //                 if ($order_deleted) {
    //                     DB::commit();
    //                     session()->flash('success', 'Berhasil Menolak Order');
    //                     return;
    //                 } else {
    //                     throw new Exception('Gagal mengubah status order.');
    //                 }
    //             } else {
    //                 throw new Exception('Gagal mengubah status order.');
    //             }
    //         } else {
    //             $reject_order = $order->update([
    //                 'status' => 1
    //             ]);
    //         }
    //         if ($reject_order) {
    //             DB::commit();
    //             session()->flash('success', 'Berhasil Menolak Order');
    //         } else {
    //             throw new Exception('Gagal mengubah status order.');
    //         }
    //     } catch (Exception $e) {
    //         DB::rollBack();
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

}
