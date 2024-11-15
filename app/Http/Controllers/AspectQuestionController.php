<?php

namespace App\Http\Controllers;

use App\Models\AspectQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AspectQuestionController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.aspect.dataTable');
        return view('master.aspect.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $categories = AspectQuestion::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                // $btn_action .= '<a href="' . route('master.aspect.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail">Detail</a>';
                $btn_action .= '<a href="' . route('master.aspect.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit">Edit</a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete">Delete</button>';

                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'description', 'action'])
            ->rawColumns(['action', 'description'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        return view('master.aspect.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required',
                'description' => 'nullable'
            ]);

            $add_aspect = AspectQuestion::lockForUpdate()->create([
                'name' => $request->name,
                'description' => $request->description
            ]);
            if ($add_aspect) {
                DB::commit();
                return redirect()->route('master.aspect.index')->with(['success' => 'Berhasil Menambahkan Kategori Quiz']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Kategori Quiz'])
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
            $aspect = AspectQuestion::find($id);
            return view('master.aspect.edit', compact('aspect'));
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

            $aspect = AspectQuestion::find($id);
            if (!is_null($aspect)) {

                $request->validate([
                    'name' => 'required',
                    'description' => 'required'
                ]);
                DB::beginTransaction();

                $aspect_update = AspectQuestion::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'description' => $request->description
                    ]);
                if ($aspect_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.aspect.index')
                        ->with(['success' => 'Berhasil Mengubah Data']);
                } else {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Mengubah Data'])
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
            $aspect = AspectQuestion::find($id);

            if (!is_null($aspect)) {
                $aspect_deleted = AspectQuestion::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($aspect_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Data Kategori Quiz');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Kategori Quiz');
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
