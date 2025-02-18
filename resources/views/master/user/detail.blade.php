@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-user-circle mr-2"></i>Detail User - {{ $user->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted"><i class="fas fa-user mr-2"></i>Username</th>
                                        <td>{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted"><i class="fas fa-id-card mr-2"></i>Nama</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted"><i class="fas fa-envelope mr-2"></i>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted"><i class="fas fa-phone-alt mr-2"></i>Nomor Handphone</th>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted"><i class="fas fa-user-tag mr-2"></i>Role Pengguna</th>
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge badge-primary m-1 py-2 px-3" style="font-size: 0.85rem;">
                                                    <i class="fas fa-shield-alt m-1"></i>{{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end pt-3">
                                @hasrole('admin')
                                    <a href="{{ route('master.user.index') }}" class="btn btn-danger">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                @else
                                    <a href="{{ route('my-account.edit') }}" class="btn btn-warning text-white">
                                        <i class="fas fa-key mr-2"></i>Ubah Password
                                    </a>
                                @endhasrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
