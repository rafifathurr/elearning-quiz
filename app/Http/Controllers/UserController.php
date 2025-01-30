<?php

namespace App\Http\Controllers;

use App\Mail\Register\SendMail;
use App\Mail\SendOtpMail;
use App\Models\HistoryPayment;
use App\Models\PaymentPackage;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAuthenticationAccess;
use App\Models\Quiz\QuizTypeUserAccess;
use App\Models\TypeUser;
use App\Models\TypeUserAccess;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.user.dataTable');
        return view('master.user.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        /**
         * Get All User
         */
        $users = User::whereNull('deleted_at')->get();

        /**
         * Datatable Configuration
         */
        $dataTable = DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($data) {

                $list_view = '<div align="center">';
                foreach ($data->getRoleNames() as $role) {
                    $list_view .= '<span class="badge bg-primary p-2 m-1" style="font-size: 0.9rem; font-weight: bold;">' . ucwords(str_replace('-', ' ', $role)) . '</span>';
                };
                $list_view .= '</div>';
                return $list_view;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.user.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('master.user.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';

                /**
                 * Validation User Logged In Equals with User Record id
                 */
                if (Auth::user()->id != $data->id) {
                    $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                }
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'username', 'email', 'role', 'action'])
            ->rawColumns(['action', 'role'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $type_user = TypeUser::whereNull('deleted_at')->get();
        $roles = Role::all();
        $payment_packages = PaymentPackage::all();

        if (Auth::check()) {
            return view('master.user.create', compact('type_user', 'roles', 'payment_packages'));
        } else {
            return view('auth.register', compact('type_user', 'roles', 'payment_packages'));
        }
    }

    public function store(Request $request)
    {
        try {
            if (auth()->check() && User::find(auth()->user()->id)->hasRole('admin')) {
                $request->validate([
                    'username' => 'required',
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'roles' => 'required|array',
                    'roles.*' => 'string|exists:roles,name',
                    'phone' => 'required',
                    'id_payment_package' => 'nullable',
                    'password' => 'required',
                    're_password' => 'required|same:password',
                ]);
            } else {
                $request->validate([
                    'username' => 'required',
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'phone' => 'required',
                    'id_payment_package' => 'nullable',
                    'password' => 'required',
                    're_password' => 'required|same:password',
                ]);
            }

            $username_check = User::whereNull('deleted_at')
                ->where('username', $request->username)
                ->first();

            $email_check = User::where('email', $request->email)
                ->first();

            if (is_null($username_check) && is_null($email_check)) {
                DB::beginTransaction();
                $otp = mt_rand(100000, 999999);
                $otp_expiry = now()->addMinutes(10);
                $add_user = User::lockforUpdate()->create([
                    'username' => $request->username,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => bcrypt($request->password)
                ]);


                if (auth()->check() && User::find(auth()->user()->id)->hasRole('admin')) {
                    foreach ($request->roles as $role) {
                        $user_role = $add_user->assignRole($role);
                    }
                } else {
                    $add_user->update([
                        'otp' => $otp,
                        'otp_expired_at' => $otp_expiry
                    ]);
                    $user_role = $add_user->assignRole('user');
                }

                //nanti apus lagi
                if ($add_user && $user_role) {
                    if (auth()->check() && User::find(auth()->user()->id)->hasRole('admin')) {
                        DB::commit();
                        return redirect()
                            ->route('master.user.index')
                            ->with(['success' => 'Berhasilkan Menambahkan User']);
                    } else {
                        Mail::to($request->email)->send(new SendOtpMail($otp));
                        DB::commit();
                        return redirect()
                            ->route('otp.verify', ['email' => $add_user->email])
                            ->with(['success' => 'Registrasi berhasil. Silakan cek email untuk Verifikasi.']);
                    }
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Registrasi Gagal'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Email atau Username Sudah Tersedia'])
                    ->withInput();
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(String $id)
    {
        try {

            $user  = User::find($id);

            if (!is_null($user)) {
                $roles = Role::all();
                $role_disabled = $id == Auth::user()->id ? 'disabled' : '';
                $type_user = TypeUser::whereNull('deleted_at')->get();
                $disabled = '';

                return view('master.user.edit', compact('user', 'roles', 'role_disabled', 'type_user', 'disabled'));
            };
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            // Validasi Input
            $request->validate([
                'username' => 'required|string|unique:users,username,' . $id,
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'roles' => 'required|array',
                'roles.*' => 'string|exists:roles,name',
                'phone' => 'required|string',
                'password' => 'nullable|string',
            ]);


            $user = User::findOrFail($id);


            DB::beginTransaction();

            // Update Data User
            $updateData = [
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            // Jika password diberikan, tambahkan ke updateData
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $user->update($updateData);


            $user->syncRoles($request->roles);


            DB::commit();

            return redirect()
                ->route('master.user.index')
                ->with(['success' => 'Berhasil Update Data User']);
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => 'Gagal Update Data User: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function show(string $id)
    {
        try {

            $user = User::find($id);
            if (!is_null($user)) {

                $type_user = TypeUser::whereNull('deleted_at')->get();
                return view('master.user.detail', compact('user', 'type_user'));
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            if (!is_null($user)) {

                $user_deleted = User::where('id', $id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                if ($user_deleted) {
                    DB::commit();
                    session()->flash('success', 'Berhasil Hapus Data User');
                } else {
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Hapus Data User');
                }
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }
}
