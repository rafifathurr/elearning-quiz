<?php

namespace App\Http\Controllers;

use App\Models\TypePackage;
use Exception;
use Illuminate\Http\Request;
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
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.typePackage.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })
            ->only(['name', 'description', 'action'])
            ->rawColumns(['description', 'action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.type_package.create');
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
            ]);

            if ($add_type_package) {
                DB::commit();
                return redirect()->route('master.typePackage.index')->with(['success' => 'Berhasil Menambahkan Tipe Paket']);
            } else {
                DB::rollBack();
                return redirect()->back()->with(['failed' => 'Gagal Menambahkan Tipe Paket']);
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
        return view('master.type_package.edit', compact('type_package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            $type_package = TypePackage::Find($id);
            if (!is_null($type_package)) {
                $request->validate([
                    'name' => 'required',
                    'description' => 'nullable'
                ]);

                $update_type_package = TypePackage::where('id', $id)->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                if ($update_type_package) {
                    DB::commit();
                    return redirect()->route('master.typePackage.index')->with(['success' => 'Berhasil Mengubah Tipe Paket']);
                } else {
                    DB::rollBack();
                    return redirect()->back()->with(['failed' => 'Gagal Mengubah Tipe Paket']);
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
                    session()->flash('success', 'Berhasil Menghapus Data Tipe Paket');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Tipe Paket');
                }
            }
        } catch (Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
