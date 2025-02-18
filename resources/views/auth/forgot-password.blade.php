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

    <body class="hold-transition login-page" style="background-color: #f4f6f9;">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-body text-center">
                    <label class="h4 pb-2 font-weight-bold text-primary">
                        <i class="fas fa-lock mr-2"></i>Reset Password
                    </label>
                    <p class="text-muted small">
                        Masukkan email Anda untuk menerima tautan reset password.
                    </p>

                    <form action="{{ route('password.email') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email Anda" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope text-primary"></span>
                                    </div>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12">
                                <button type="submit" id="login" class="btn btn-primary btn-block font-weight-bold"
                                    style="transition: 0.3s; background: linear-gradient(45deg, #007bff, #0056b3); border: none;">
                                    <i class="fas fa-paper-plane"></i> Kirim Tautan Reset
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row justify-content-center mt-4">
                        <div class="col-12 text-left">
                            <a href="{{ route('login') }}" class="text-primary font-weight-bold">
                                <i class="fas fa-arrow-left"></i> Kembali ke Login
                            </a>
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
