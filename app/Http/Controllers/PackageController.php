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
                $btn_action .= '<a href="' . route('master.package.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })
            ->addColumn('class', function ($data) {
                return !is_null($data->class) && $data->class > 0 ? $data->class . 'x Pertemuan' : '-';
            })
            ->addColumn('price', function ($data) {
                $price = '<div>' . 'Rp. ' . number_format($data->price, 0, ',', '.');

                $price .= '<div>';
                return $price;
            })
            ->addColumn('quiz', function ($data) {
                $list_view = '<div align="center">';
                foreach ($data->packageTest as $package) {
                    $list_view .= '<span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">' . $package->quiz->name . '</span>';
                };
                $list_view .= '</div>';
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
                'class' => 'nullable',
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
                return redirect()->route('master.package.index')->with(['success' => 'Berhasil Menambahkan Paket Test']);
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Menambahkan Paket Test'])
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
            $data['package'] = Package::find($id);
            $data['quizes'] = Quiz::whereNull('deleted_at')->get();
            return view('master.package_payment.edit', $data);
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
                    'price' => 'required',
                    'class' => 'nullable',
                    'quiz_id' => 'required'
                ]);
                DB::beginTransaction();

                $package_update = Package::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'class' => $request->class
                    ]);
                $deleted_package_test = PackageTest::where('package_id', $package->id)->delete();
                if ($package_update && $deleted_package_test) {
                    $packages_test = [];
                    foreach ($request->quiz_id as $quiz) {
                        $packages_test[] = [
                            'package_id' => $package->id,
                            'quiz_id' => $quiz
                        ];
                    }
                    $add_package_test = PackageTest::insert($packages_test);
                    if ($add_package_test) {

                        DB::commit();
                        return redirect()
                            ->route('master.package.index')
                            ->with(['success' => 'Berhasil Mengubah Data Paket Test']);
                    } else {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Mengubah Data Paket Test'])
                            ->withInput();
                    }
                } else {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Mengubah Data Paket Test'])
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
                $package_deleted = Package::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                if ($package_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Menghapus Data Paket Test');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Menghapus Data Paket Test');
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
