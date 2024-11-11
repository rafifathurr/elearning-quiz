<?php

namespace App\Http\Controllers;

use App\Models\Quiz\TypeQuiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TypeQuizController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.category.dataTable');
        return view('master.category.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $categories = TypeQuiz::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                // $btn_action .= '<a href="' . route('master.category.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail">Detail</a>';
                $btn_action .= '<a href="' . route('master.category.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit">Edit</a>';
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
        return view('master.category.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required',
                'description' => 'required'
            ]);

            $add_category = TypeQuiz::lockForUpdate()->create([
                'name' => $request->name,
                'description' => $request->description
            ]);
            if ($add_category) {
                DB::commit();
                return redirect()->route('master.category.index')->with(['success' => 'Berhasil Menambahkan Kategori Quiz']);
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
                ->withInput();;
        }
    }

    public function edit(string $id)
    {
        try {
            $category = TypeQuiz::find($id);
            return view('master.category.edit', compact('category'));
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

            $category = TypeQuiz::find($id);
            if (!is_null($category)) {

                $request->validate([
                    'name' => 'required',
                    'description' => 'required'
                ]);
                DB::beginTransaction();

                $category_update = TypeQuiz::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'description' => $request->description
                    ]);
                if ($category_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.category.index')
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
            $category = TypeQuiz::find($id);

            if (!is_null($category)) {
                $category_deleted = TypeQuiz::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($category_deleted) {
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
