<?php

namespace App\Http\Controllers;

use App\Models\DateClass;
use App\Models\Package;
use App\Models\PackageDate;
use App\Models\PackageTest;
use App\Models\Quiz\Quiz;
use App\Models\TypePackage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.package.dataTable');
        return view('master.package_payment.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $userId = Auth::user()->id;
        if (User::find($userId)->hasRole('admin')) {
            $categories = Package::whereNull('deleted_at')->get();
        } else {
            $categories = Package::whereNull('deleted_at')
                ->whereHas('typePackage.packageAccess', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
        }

        $dataTable = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.package.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning m-1" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger m-1" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })
            ->addColumn('class', function ($data) {
                return !is_null($data->class) && $data->class > 0 ? $data->class . 'x Pertemuan' : '-';
            })
            ->addColumn('max_member', function ($data) {
                return !is_null($data->max_member) && $data->max_member > 0 ? $data->max_member . ' Peserta' : '-';
            })
            ->addColumn('price', function ($data) {
                $price = '<div>' . 'Rp. ' . number_format($data->price, 0, ',', '.');

                $price .= '<div>';
                return $price;
            })
            ->addColumn('type_package', function ($data) {
                return $data->typePackage ? $data->typePackage->name : '-';
            })
            ->addColumn('status', function ($data) {
                // Cek apakah status aktif (1) atau tidak (0)
                $checked = $data->status == 1 ? 'checked' : ''; // Jika status 1, maka checkbox tercentang

                $toggle_status = '<div class="custom-control custom-switch">';
                $toggle_status .= '<input type="checkbox" class="custom-control-input" id="status' . $data->id . '" ' . $checked . '>';
                $toggle_status .= '<label class="custom-control-label" for="status' . $data->id . '">' . ($data->status == 1 ? 'Aktif' : 'Tidak Aktif') . '</label>';
                $toggle_status .= '</div>';

                return $toggle_status;
            })

            ->addColumn('quiz', function ($data) {
                $list_view = '<div align="center">';
                foreach ($data->packageTest as $package) {
                    $list_view .= '<span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">' . $package->quiz->name . '</span>';
                };
                $list_view .= '</div>';
                return $list_view;
            })

            ->addColumn('date_class', function ($data) {
                $list_view = '<div align="center">';
                foreach ($data->packageDate as $package) {
                    $short_text = Str::limit($package->dateClass->name, 20); // Potong teks jadi 20 karakter
                    $list_view .= '<span class="badge bg-primary p-2 m-1 custom-tooltip" style="font-size: 0.9rem; font-weight: bold;" title="' . e($package->dateClass->name) . '">' . $short_text . '</span>';
                }
                $list_view .= '</div>';
                return $list_view;
            })



            ->only(['id', 'name', 'class', 'max_member', 'price', 'quiz', 'date_class', 'type_package', 'status', 'action'])
            ->rawColumns(['status', 'action', 'price', 'quiz', 'date_class'])
            ->make(true);

        return $dataTable;
    }

    public function updateStatus(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->status = $request->status;
        $package->save();

        return response()->json(['success' => true, 'status' => $package->status ? 'Aktif' : 'Tidak Aktif']);
    }


    public function create()
    {
        $quizes = Quiz::whereNull('deleted_at')->get();
        $dates = DateClass::whereNull('deleted_at')->get();
        $userId = Auth::user()->id;
        if (User::find($userId)->hasRole('admin')) {
            $types = TypePackage::where('id_parent', 0)->whereNull('deleted_at')->with('children')->get();
        } else {
            $types = TypePackage::where('id_parent', 0)
                ->whereNull('deleted_at')
                ->with(['children' => function ($query) use ($userId) {
                    $query->whereNull('deleted_at')
                        ->whereHas('packageAccess', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                }])
                ->where(function ($query) use ($userId) {
                    // Cek parent yang memiliki akses langsung
                    $query->whereHas('packageAccess', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                        // Atau parent yang punya children dengan akses
                        ->orWhereHas('children.packageAccess', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                })
                ->get();
        }
        return view('master.package_payment.create', compact('quizes', 'types', 'dates'));
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'class' => 'nullable',
                'max_member' => 'nullable',
                'id_type_package' => 'required'
            ]);

            // Menambahkan package
            $add_package = Package::lockForUpdate()->create([
                'name' => $request->name,
                'price' => $request->price,
                'class' => $request->class,
                'max_member' => $request->max_member,
                'id_type_package' => $request->id_type_package,
                'description' => $request->description
            ]);

            // Cek apakah ada quiz yang dipilih
            if ($request->has('quiz_id') && !empty($request->quiz_id)) {
                $packages_test = [];
                foreach ($request->quiz_id as $quiz) {
                    $packages_test[] = [
                        'package_id' => $add_package->id,
                        'quiz_id' => $quiz
                    ];
                }
                // Insert hanya jika ada quiz_id yang dipilih
                $add_package_test = PackageTest::insert($packages_test);
            } else {
                $add_package_test = true; // Set true jika tidak ada quiz
            }

            // Cek apakah ada Jadwal yang dipilih
            if ($request->has('date_class_id') && !empty($request->date_class_id)) {
                $package_date = [];
                foreach ($request->date_class_id as $date) {
                    $package_date[] = [
                        'package_id' => $add_package->id,
                        'date_class_id' => $date
                    ];
                }
                // Insert hanya jika ada date_class_id yang dipilih
                $add_package_date = PackageDate::insert($package_date);
            } else {
                $add_package_date = true; // Set true jika tidak ada quiz
            }

            // Jika insert berhasil
            if ($add_package && $add_package_test && $add_package_date) {
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
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }


    public function edit(string $id)
    {
        try {
            $package = Package::findOrFail($id);
            $quizes = Quiz::whereNull('deleted_at')->get();
            $dates = DateClass::whereNull('deleted_at')->get();
            $userId = Auth::user()->id;
            if (User::find($userId)->hasRole('admin')) {
                $types = TypePackage::where('id_parent', 0)->whereNull('deleted_at')->with('children')->get();
            } else {
                $types = TypePackage::where('id_parent', 0)
                    ->whereNull('deleted_at')
                    ->with(['children' => function ($query) use ($userId) {
                        $query->whereNull('deleted_at')
                            ->whereHas('packageAccess', function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            });
                    }])
                    ->where(function ($query) use ($userId) {
                        // Cek parent yang memiliki akses langsung
                        $query->whereHas('packageAccess', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })
                            // Atau parent yang punya children dengan akses
                            ->orWhereHas('children.packageAccess', function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            });
                    })
                    ->get();
            }

            return view('master.package_payment.edit', compact('package', 'quizes', 'types', 'dates'));
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => $e->getMessage()])->withInput();
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
                    'max_member' => 'nullable',
                    'quiz_id' => 'nullable',
                    'id_type_package' => 'required'
                ]);
                DB::beginTransaction();

                // Update package
                $package_update = Package::where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'class' => $request->class,
                        'max_member' => $request->max_member,
                        'id_type_package' => $request->id_type_package,
                        'description' => $request->description
                    ]);

                // Hapus package_test lama
                $deleted_package_test = PackageTest::where('package_id', $package->id)->delete();

                $deleted_package_date = PackageDate::where('package_id', $package->id)->delete();

                if ($package_update) {
                    // Cek apakah ada quiz_id yang dipilih
                    if ($request->has('quiz_id') && !empty($request->quiz_id)) {
                        $packages_test = [];
                        foreach ($request->quiz_id as $quiz) {
                            $packages_test[] = [
                                'package_id' => $package->id,
                                'quiz_id' => $quiz
                            ];
                        }
                        // Insert ke PackageTest jika ada quiz_id
                        $add_package_test = PackageTest::insert($packages_test);
                    } else {
                        // Jika tidak ada quiz_id, set add_package_test = true
                        $add_package_test = true;
                    }

                    // Cek apakah ada date_class_id yang dipilih
                    if ($request->has('date_class_id') && !empty($request->date_class_id)) {
                        $packages_date = [];
                        foreach ($request->date_class_id as $date) {
                            $packages_date[] = [
                                'package_id' => $package->id,
                                'date_class_id' => $date
                            ];
                        }
                        // Insert ke PackageDate jika ada date_class_id
                        $add_package_date = PackageDate::insert($packages_date);
                    } else {
                        // Jika tidak ada date_class_id, set add_package_date = true
                        $add_package_date = true;
                    }

                    if ($add_package_test && $add_package_date) {
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
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
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
