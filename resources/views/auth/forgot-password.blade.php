@extends('layouts.main')
@section('section')
    <style>
        .login-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>

    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <label class="h5 pb-3"><i class="fas fa-list mr-2"></i>Brata Cerdas</label>
                    <form action="{{ route('password.email') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Masukan Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-3 d-flex justify-content-end">
                            <div class="col-12 text-center">
                                <button type="submit" id="login"
                                    class="btn btn-primary btn-block font-weight-bold">Request Password Baru</button>
                            </div>
                        </div>
                    </form>
                    <div class="row justify-content-center mt-4">
                        <div class="col-12 text-left">
                            <a href="{{ route('login') }}" class="">Login</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </body>
@endsection
