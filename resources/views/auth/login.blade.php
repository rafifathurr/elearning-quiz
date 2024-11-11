@extends('layouts.main')
@section('section')
    <style>
        .login-box {
            position: fixed;
            /* Atur posisi menjadi fixed */
            top: 50%;
            /* Posisi vertikal di tengah */
            left: 50%;
            /* Posisi horizontal di tengah */
            transform: translate(-50%, -50%);
            /* Menggeser form agar tepat di tengah */
            z-index: 1000;
            /* Pastikan di atas elemen lain */
        }
    </style>

    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <label class="h5 pb-3"><i class="fas fa-list mr-2"></i>Elearning - Quiz</label>
                    <form action="{{ route('authenticate') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Email or Username">
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

                        <div class="row pt-3">
                            <div class="col-8">
                                <a href="{{ route('account.create') }}" class="">Register Akun</a>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold">Log In</button>
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
