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
                $btn_action .= '<a href="' . route('master.aspect.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'type_aspect', 'action'])
            ->rawColumns(['action'])
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
                'type_aspect' => 'required',
                'description' => 'nullable'
            ]);

            $add_aspect = AspectQuestion::lockForUpdate()->create([
                'name' => $request->name,
                'type_aspect' => $request->type_aspect,
                'description' => $request->description
            ]);
            if ($add_aspect) {
                DB::commit();
                return redirect()->route('master.aspect.index')->with(['success' => 'Berhasil Menambahkan Aspek Pertanyaan']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Aspek Pertanyaan'])
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
                    'type_aspect' => 'required',
                    'description' => 'nullable'
                ]);
                DB::beginTransaction();

                $aspect_update = AspectQuestion::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'type_aspect' => $request->type_aspect,
                        'description' => $request->description
                    ]);
                if ($aspect_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.aspect.index')
                        ->with(['success' => 'Berhasil Mengubah Data Aspek Pertanyaan']);
                } else {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Mengubah Data Aspek Pertanyaan'])
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
                    session()->flash('success', 'Berhasil Menghapus Data Aspek Pertanyaan');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Aspek Pertanyaan');
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()])
                ->withInput();
        }
    }

    public function getAspectsByTypeAspect(Request $request)
    {
        $typeAspect = $request->input('type_aspect');

        // Ambil aspek berdasarkan nilai type_aspect
        $aspects = AspectQuestion::where('type_aspect', $typeAspect)
            ->whereNull('deleted_at')
            ->get(['id', 'name']);

        return response()->json($aspects);
    }
}
