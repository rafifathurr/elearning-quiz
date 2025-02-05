<?php

namespace App\Http\Controllers;

use App\Models\DateClass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DateClassController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.dateclass.dataTable');
        return view('master.dateclass.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $dates = DateClass::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($dates)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.dateclass.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'date_code', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        return view('master.dateclass.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required',
                'date_code' => 'required',
            ]);

            $add_date = DateClass::lockForUpdate()->create([
                'name' => $request->name,
                'date_code' => $request->date_code
            ]);
            if ($add_date) {
                DB::commit();
                return redirect()->route('master.dateclass.index')->with(['success' => 'Berhasil Menambahkan Jadwal Kelas']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Jadwal Kelas'])
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
            $dateclass = DateClass::find($id);
            return view('master.dateclass.edit', compact('dateclass'));
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

            $dateclass = DateClass::find($id);
            if (!is_null($dateclass)) {

                $request->validate([
                    'name' => 'required',
                    'date_code' => 'required'
                ]);
                DB::beginTransaction();

                $date_update = DateClass::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'date_code' => $request->date_code
                    ]);
                if ($date_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.dateclass.index')
                        ->with(['success' => 'Berhasil Mengubah Data Jadwal Kelas']);
                } else {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Mengubah Data Jadwal Kelas'])
                        ->withInput();
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $dateclass = DateClass::find($id);

            if (!is_null($dateclass)) {
                $date_deleted = DateClass::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($date_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Data Jadwal Kelas');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Jadwal Kelas');
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
