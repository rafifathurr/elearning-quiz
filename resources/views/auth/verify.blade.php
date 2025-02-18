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
            <div class="card card-outline card-primary">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary">
                        <i class="fas fa-shield-alt mr-2"></i>Verifikasi Akun
                    </h4>
                    <p class="text-muted">
                        Kami telah mengirimkan kode verifikasi ke email <strong>{{ $email }}</strong>.
                        Silakan masukkan kode tersebut untuk melanjutkan.
                    </p>

                    <form action="{{ route('otp.verify.post') }}" method="post">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="otp"
                                    class="form-control text-center font-weight-bold @error('otp') is-invalid @enderror"
                                    placeholder="Kode OTP" maxlength="6" style="letter-spacing: 5px; font-size: 20px;">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-key text-primary"></span>
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold"
                                    style="transition: 0.3s; background: linear-gradient(45deg, #007bff, #0056b3); border: none;">
                                    <i class="fas fa-paper-plane"></i> Verifikasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection
