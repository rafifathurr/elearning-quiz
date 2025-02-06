@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title font-weight-bold">Detail User - {{ $user->name }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="username">Username </label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $user->username }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="name">Nama </label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="email">Email </label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="email">Nomor Handhphone </label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $user->phone }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="role">Role Pengguna </label>
                                <div class="col-sm-9 col-form-label">
                                    @foreach ($user->roles as $role)
                                        <span class="badge badge-primary mr-2 py-2"
                                            style="font-size: 0.8rem;">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-flex pt-3 ">

                                @hasrole('user')
                                    <a href="{{ route('my-account.edit') }}" class="btn btn-warning text-white">Ubah
                                        Password</a>
                                @else
                                    <a href="{{ route('master.user.index') }}" class="btn btn-danger mr-2">Kembali</a>
                                @endhasrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
