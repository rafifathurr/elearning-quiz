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
                    <form action="{{ route('authenticate') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Email Atau Username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
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
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                <div class="input-group-append">
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
                        <div class="row pt-3 d-flex justify-content-end">
                            <div class="col-4">
                                <button type="submit" id="login" class="btn btn-primary btn-block font-weight-bold">Log
                                    In</button>
                            </div>
                        </div>
                    </form>
                    <div class="social-auth-links text-center mb-3">
                        <p>- Atau -</p>
                        <a href="{{ url('/auth/google') }}" class="btn btn-block btn-primary">
                            <i class="fab fa-google mr-2"></i> Login Menggunakan Google
                        </a>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-12 text-center">
                            Tidak Punya Akun ? <a href="{{ route('account.create') }}" class="">Daftar Akun</a>
                        </div>
                    </div>
                    {{-- <div class="row justify-content-center mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('password.request') }}" class="">Lupa Password</a>
                        </div>
                    </div> --}}
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
        </script>
    @endpush
@endsection
