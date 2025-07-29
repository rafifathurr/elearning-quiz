<?php

namespace App\Http\Controllers;

use App\Models\PackageAccess;
use App\Models\TypePackage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TypePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datatable_route = route('master.typePackage.dataTable');

        return view('master.type_package.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $type_package = TypePackage::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($type_package)
            ->addIndexColumn()
            ->addColumn('parent', function ($data) {
                return '<div align="center"> <span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">'
                    . ($data->parent ? $data->parent->name : '')
                    . '</span></div>';
            })

            ->addColumn('access', function ($data) {
                $span_access = '<div align="center">';
                if ($data->packageAccess && count($data->packageAccess) > 0) {
                    $span_access .=
                        '<span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;"><i class="fas fa-lock mr-1"></i> '
                        .  count($data->packageAccess)
                        . ' Package-Manager' . '</span>';
                } else {
                    $span_access .= '<span class="text-muted font-italic">Belum atur akses</span>';
                }
                $span_access .= '<div>';
                return $span_access;
            })


            ->addColumn('action', function ($data) {
                if (User::find(Auth::user()->id)->hasRole('admin')) {
                    $btn_action = '<div align="center">';
                    $btn_action .= '<a href="' . route('master.typePackage.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                    $btn_action .= '<div>';
                    return $btn_action;
                } else {
                    return null;
                }
            })
            ->only(['name', 'description', 'parent', 'access', 'action'])
            ->rawColumns(['description', 'parent', 'access', 'action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TypePackage::where('id_parent', 0)->whereNull('deleted_at')->with('children')->get();
        $users = User::whereNull('deleted_at')->where('status', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'package-manager');
        })->get();
        return view('master.type_package.create', compact('types', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'description' => 'nullable'
            ]);

            $add_type_package = TypePackage::lockForUpdate()->create([
                'name' => $request->name,
                'description' => $request->description,
                'id_parent' => isset($request->id_parent) ? $request->id_parent : 0,
            ]);

            if ($request->has('user_id') && !empty($request->user_id)) {
                $packages_test = [];
                foreach ($request->user_id as $user) {
                    $packages_test[] = [
                        'type_package_id' => $add_type_package->id,
                        'user_id' => $user
                    ];
                }
                // Insert hanya jika ada user_id yang dipilih
                $add_package_access = PackageAccess::insert($packages_test);
            } else {
                $add_package_access = true; // Set true jika tidak ada user
            }

            if ($add_type_package && $add_package_access) {
                DB::commit();
                return redirect()->route('master.typePackage.index')->with(['success' => 'Berhasil Menambahkan Kategori Paket']);
            } else {
                DB::rollBack();
                return redirect()->back()->with(['failed' => 'Gagal Menambahkan Kategori Paket']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return
                redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $type_package = TypePackage::find($id);
        $types = TypePackage::where('id_parent', 0)->whereNull('deleted_at')->with('children')->get();
        $users = User::whereNull('deleted_at')->where('status', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'package-manager');
        })->get();
        return view('master.type_package.edit', compact('type_package', 'types', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        DB::beginTransaction();
        try {
            $type_package = TypePackage::Find($id);
            if (!is_null($type_package)) {
                $request->validate([
                    'name' => 'required',
                    'description' => 'nullable'
                ]);

                if (!empty($request->id_parent) && $request->id_parent == $id) {
                    DB::rollBack();
                    return redirect()->back()->with(['failed' => 'Anda tidak boleh memilih kategori paket ini sebagai parent']);
                }

                // Update data
                $update_type_package = TypePackage::where('id', $id)->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'id_parent' => isset($request->id_parent) ? $request->id_parent : 0,
                ]);

                // Hapus akses sebelumnya
                PackageAccess::where('type_package_id', $type_package->id)->delete();

                // Tambahkan akses baru jika ada user_id
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $packages_test = [];
                    foreach ($request->user_id as $user) {
                        $packages_test[] = [
                            'type_package_id' => $type_package->id,
                            'user_id' => $user
                        ];
                    }
                    // Insert hanya jika ada user_id yang dipilih
                    $update_package_access = PackageAccess::insert($packages_test);
                } else {
                    $update_package_access = true; // Set true jika tidak ada user
                }

                if ($update_type_package && $update_package_access) {
                    DB::commit();
                    return redirect()->route('master.typePackage.index')->with(['success' => 'Berhasil Mengubah Kategori Paket']);
                } else {
                    DB::rollBack();
                    return redirect()->back()->with(['failed' => 'Gagal Mengubah Kategori Paket']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return
                redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $type_package = TypePackage::find($id);

            if (!is_null($type_package)) {
                $deleted_type_package = TypePackage::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                if ($deleted_type_package) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Data Kategori Paket');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Kategori Paket');
                }
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
