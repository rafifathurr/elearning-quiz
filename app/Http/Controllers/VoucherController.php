<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\Voucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.voucher.dataTable');
        return view('master.voucher.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $categories = Voucher::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('package_name', function ($data) {
                return $data->package->name;
            })
            ->addColumn('discount', function ($data) {
                return !is_null($data->discount) && $data->discount > 0 ? $data->discount . ' %' : '-';
            })
            ->addColumn('fixed_price', function ($data) {
                if (!is_null($data->fixed_price) && $data->fixed_price > 0) {
                    $price = '<div>' . 'Rp. ' . number_format($data->fixed_price, 0, ',', '.');

                    $price .= '<div>';
                } else {
                    $price = null;
                }

                return $price;
            })
            // ->addColumn('voucher_price', function ($data) {
            //     $voucher_price = '<div>' . 'Rp. ' . number_format($data->voucher_price, 0, ',', '.');

            //     $voucher_price .= '<div>';
            //     return $voucher_price;
            // })
            ->addColumn('action', function ($data) {
                $user = User::find(Auth::user()->id);
                $btn_action = '<div class="text-center">';

                if ($user->hasAnyRole('counselor', 'class-operator')) {
                    $btn_action .= '<button class="btn btn-sm btn-primary m-1" 
                        onclick="checkOutVoucher(' . $data->id . ', \'' . $data->name . '\', ' . $data->voucher_price . ')" 
                        title="Get">
                        <i class="fas fa-gift mr-2"></i>Ambil Voucher
                    </button>';
                }

                if ($user->hasAnyRole('admin', 'package-manager')) {
                    $btn_action .= '<a href="' . route('master.voucher.edit', ['id' => $data->id]) . '" 
                        class="btn btn-sm btn-warning m-1" title="Edit">
                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                    </a>';

                    $btn_action .= '<button class="btn btn-sm btn-danger m-1" 
                        onclick="destroyRecord(' . $data->id . ')" title="Delete">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>';
                }

                if ($user->hasRole('manager')) {
                    $btn_action .= '<span class="text-muted">-</span>';
                }

                $btn_action .= '</div>';
                return $btn_action;
            })



            ->only(['name', 'package_name', 'discount', 'fixed_price', 'action'])
            ->rawColumns(['action', 'package_name', 'discount', 'fixed_price'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $packages = Package::whereNull('deleted_at')->get();
        return view('master.voucher.create', compact('packages'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $baseRules = [
                'name' => 'required',
                'type_voucher' => 'required|in:discount,fixed_price',
                'voucher_price' => 'nullable|numeric',
                'package_id' => 'required|integer',
                'description' => 'nullable|string',
            ];

            // Tambahkan validasi berdasarkan type_voucher
            if ($request->type_voucher === 'discount') {
                $baseRules['discount'] = 'required|numeric';
            } elseif ($request->type_voucher === 'fixed_price') {
                $baseRules['fixed_price'] = 'required|numeric';
            }

            $request->validate($baseRules);

            $add_voucher = Voucher::lockForUpdate()->create([
                'name' => $request->name,
                'discount' => $request->discount,
                'package_id' => $request->package_id,
                'type_voucher' => $request->type_voucher,
                'fixed_price' => $request->fixed_price,
                'voucher_price' => $request->voucher_price,
                'description' => $request->description
            ]);
            if ($add_voucher) {
                DB::commit();
                return redirect()->route('master.voucher.index')->with(['success' => 'Berhasil Menambahkan Voucher Paket']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Voucher Paket'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();
        }
    }
    public function edit(string $id)
    {
        try {
            $voucher = Voucher::find($id);
            $packages = Package::whereNull('deleted_at')->get();
            return view('master.voucher.edit', compact('voucher', 'packages'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();;
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $voucher = Voucher::find($id);
            if (!$voucher) {
                return redirect()->back()->with(['failed' => 'Voucher tidak ditemukan'])->withInput();
            }

            $baseRules = [
                'name' => 'required',
                'type_voucher' => 'required|in:discount,fixed_price',
                'package_id' => 'required|integer',
                'description' => 'nullable|string',
            ];

            // Tambahkan validasi berdasarkan type_voucher
            if ($request->type_voucher === 'discount') {
                $baseRules['discount'] = 'required|numeric';
            } elseif ($request->type_voucher === 'fixed_price') {
                $baseRules['fixed_price'] = 'required|numeric';
            }

            $validated = $request->validate($baseRules);

            DB::beginTransaction();

            $voucher->update([
                'name' => $validated['name'],
                'type_voucher' => $validated['type_voucher'],
                'package_id' => $validated['package_id'],
                'description' => $validated['description'] ?? null,
                'discount' => $validated['type_voucher'] === 'discount' ? $validated['discount'] : null,
                'fixed_price' => $validated['type_voucher'] === 'fixed_price' ? $validated['fixed_price'] : null,
            ]);

            DB::commit();
            return redirect()->route('master.voucher.index')
                ->with(['success' => 'Berhasil Mengubah Data Voucher Paket']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['failed' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $aspect = Voucher::find($id);

            if (!is_null($aspect)) {
                $aspect_deleted = Voucher::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($aspect_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Data Voucher Paket');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Voucher Paket');
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
