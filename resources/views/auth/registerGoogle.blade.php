@extends('layouts.main')
@section('section')
    <style>
        .register-box {
            width: 760px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;

        }

        @media (max-width: 576px) {
            .register-box {
                margin-top: .5rem;
                width: 80%;
            }
        }
    </style>

    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <label class="h5 pb-3"><i class="fas fa-list mr-2"></i>Register Akun</label>
                    <form action="{{ route('auth.storeDataGoogle') }}" method="post">
                        @csrf
                        <input type="hidden" name="name" value="{{ old('name', session('name')) }}">
                        <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
                        <input type="hidden" name="google_id" value="{{ old('google_id', session('google_id')) }}">

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror" placeholder="Username"
                                        value="{{ old('username') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Nomor Handphone" value="{{ old('phone') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-phone-alt"></span>
                                        </div>
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection
