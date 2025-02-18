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
                    <h4 class="font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-2"></i>Selamat Datang!
                    </h4>
                    <p class="text-muted">Silakan masuk untuk melanjutkan.</p>

                    <form action="{{ route('authenticate') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Email atau Username" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope text-primary"></span>
                                    </div>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control border-right-0 @error('password') is-invalid @enderror"
                                    placeholder="Password" required>
                                <div class="input-group-append">
                                    <a href="javascript:;" onclick="togglePasswordVisibility()"
                                        class="input-group-text bg-transparent border-left-0">
                                        <i class='fas fa-eye text-secondary' style="opacity: 0.5;" id="password-icon"></i>
                                    </a>
                                </div>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock text-primary"></span>
                                    </div>
                                </div>
                                @error('password')
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
                                    <i class="fas fa-sign-in-alt"></i> Masuk
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="social-auth-links text-center mt-3">
                        <p class="text-muted">Atau masuk dengan</p>
                        <a href="{{ url('/auth/google') }}" class="btn btn-block btn-danger"
                            style="transition: 0.3s; background: linear-gradient(45deg, #db4437, #b23121);">
                            <i class="fab fa-google mr-2"></i> Google
                        </a>
                    </div>

                    <div class="row justify-content-center mt-4">
                        <div class="col-12 text-center">
                            <p>Belum punya akun? <a href="{{ route('account.create') }}"
                                    class="text-primary font-weight-bold">Daftar sekarang</a></p>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <a href="{{ route('password.request') }}" class="text-muted">
                                <i class="fas fa-key"></i> Lupa Password?
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

    @push('javascript-bottom')
        <script>
            document.getElementById('login').addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pengalihan default

                // Hapus waktu dari localStorage ketika tombol "Login" diklik
                localStorage.removeItem('remainingTime');

                // Kirim form setelah menghapus remainingTime
                this.closest('form').submit(); // Menyubmit form
            });

            function togglePasswordVisibility() {
                const passwordField = document.getElementById('password');
                const passwordIcon = document.getElementById('password-icon');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    passwordIcon.classList.add('fa-eye');
                    passwordIcon.classList.remove('fa-eye-slash');
                }
            }
        </script>
    @endpush
@endsection
