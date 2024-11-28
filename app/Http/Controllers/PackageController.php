<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageTest;
use App\Models\Quiz\Quiz;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.package.dataTable');
        return view('master.package_payment.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $categories = Package::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                // $btn_action .= '<a href="' . route('master.package.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail">Detail</a>';
                $btn_action .= '<a href="' . route('master.package.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit">Edit</a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete">Delete</button>';

                $btn_action .= '<div>';
                return $btn_action;
            })
            ->addColumn('price', function ($data) {
                $price = '<div>' . 'Rp. ' . number_format($data->price, 0, ',', '.');

                $price .= '<div>';
                return $price;
            })
            ->addColumn('quiz', function ($data) {
                $list_view = '<ul>';
                foreach ($data->packageTest as $package) {
                    $list_view .= '<li>' . $package->quiz->name . '</li>';
                };
                $list_view .= '</ul>';
                return $list_view;
            })


            ->only(['name', 'class', 'price', 'quiz', 'action'])
            ->rawColumns(['action', 'price', 'quiz'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $quizes = Quiz::whereNull('deleted_at')->get();
        return view('master.package_payment.create', compact('quizes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'class' => 'required',
                'quiz_id' => 'required'
            ]);

            $add_package = Package::lockForUpdate()->create([
                'name' => $request->name,
                'price' => $request->price,
                'class' => $request->class
            ]);
            $packages_test = [];
            foreach ($request->quiz_id as $quiz) {
                $packages_test[] = [
                    'package_id' => $add_package->id,
                    'quiz_id' => $quiz
                ];
            }
            $add_package_test = PackageTest::insert($packages_test);

            if ($add_package && $add_package_test) {
                DB::commit();
                return redirect()->route('master.package.index')->with(['success' => 'Berhasil Menambahkan Aspek Pertanyaan']);
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
            $package = Package::find($id);
            return view('master.package_payment.edit', compact('package'));
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

            $package = Package::find($id);
            if (!is_null($package)) {

                $request->validate([
                    'name' => 'required',
                    'type_aspect' => 'required',
                    'description' => 'nullable'
                ]);
                DB::beginTransaction();

                $aspect_update = Package::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'type_aspect' => $request->type_aspect,
                        'description' => $request->description
                    ]);
                if ($aspect_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.package.index')
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
            $package = Package::find($id);

            if (!is_null($package)) {
                $aspect_deleted = Package::where('id', $id)->update([
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
}
