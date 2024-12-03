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
                    <label class="h5 pb-3"><i class="fas fa-list mr-2"></i>Verifikasi Akun</label>
                    <form action="{{ route('otp.verify.post') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <div class="input-group">
                                <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror"
                                    placeholder="Kode Verifikasi">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('otp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold">Kirim</button>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </body>
@endsection
