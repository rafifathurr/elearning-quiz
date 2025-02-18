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
                        <i class="fas fa-key mr-2"></i>Reset Password
                    </h4>
                    <p class="text-muted">
                        Masukkan password baru untuk akun Anda dan pastikan mudah diingat.
                    </p>

                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Input Password Baru -->
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control border-right-0 @error('password') is-invalid @enderror"
                                    placeholder="Masukkan Password Baru" required>
                                <div class="input-group-append">
                                    <a href="javascript:;" class="input-group-text bg-transparent"
                                        onclick="togglePassword('password', 'password-icon')">
                                        <i class='fas fa-eye text-secondary' id="password-icon"></i>
                                    </a>
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control border-right-0 @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Konfirmasi Password Baru" required>
                                <div class="input-group-append">
                                    <a href="javascript:;" class="input-group-text bg-transparent"
                                        onclick="togglePassword('password_confirmation', 'password-confirmation-icon')">
                                        <i class='fas fa-eye text-secondary' id="password-confirmation-icon"></i>
                                    </a>
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Ubah Password -->
                        <div class="row pt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold"
                                    style="transition: 0.3s; background: linear-gradient(45deg, #007bff, #0056b3); border: none;">
                                    <i class="fas fa-sync-alt"></i> Ubah Password
                                </button>
                            </div>
                        </div>

                        <!-- Link Kembali ke Login -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    <a href="{{ route('login') }}" class="text-primary font-weight-bold">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script untuk Toggle Password Visibility -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const password = document.getElementById('password');
                const re_password = document.getElementById('password_confirmation');

                function checkRePassword(event) {
                    var input = event.target;

                    if (input.id === 'password_confirmation' && input.value !== password.value) {
                        input.setCustomValidity('Password Tidak Sama.');
                    } else {
                        input.setCustomValidity('');
                    }
                }
                re_password.addEventListener('input', checkRePassword);
            });

            function togglePassword(inputId, iconId) {
                let input = document.getElementById(inputId);
                let icon = document.getElementById(iconId);

                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("fa-eye-slash", "fa-eye");
                }
            }
        </script>
    </body>
@endsection
