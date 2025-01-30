@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pengguna #{{ $user->id }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card ">
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
                            <a href="{{ route('master.user.index') }}" class="btn btn-sm btn-primary"><i
                                    class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
