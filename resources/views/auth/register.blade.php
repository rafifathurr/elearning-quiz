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
                    <label class="h5 pb-3"><i class="fas fa-list mr-2"></i>Register Account</label>
                    <form action="{{ route('account.store') }}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap"
                                        value="{{ old('name') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                    @error('name')
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
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                        value="{{ old('email') }}">
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
                        <div class="col-md-12">
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
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" name="re_password"
                                        class="form-control @error('re_password') is-invalid @enderror"
                                        placeholder="Re-Password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                    @error('re_password')
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
                                    <select class="form-control @error('type_of_user[]') is-invalid @enderror"
                                        name="type_of_user[]" id="type_of_user" data-placeholder="Pilih Tipe User"
                                        style="width: 100%;">
                                        @foreach ($type_user as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                    @error('type_of_user[]')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-control @error('id_payment_package') is-invalid @enderror"
                                        id="id_payment_package" name="id_payment_package" required>
                                        <option disabled hidden></option>
                                        @foreach ($payment_packages as $payment)
                                            @if (!is_null(old('id_payment_package')) && old('id_payment_package') == $payment->id)
                                                <option value="{{ $payment->id }}" selected>
                                                    {{ $payment->name }}
                                                </option>
                                            @else
                                                <option value="{{ $payment->id }}">{{ $payment->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="input-group-text">
                                        <span class="fas fa-wallet"></span>
                                    </div>
                                    @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-12">
                                <button type="submit"
                                    class="btn btn-primary btn-block font-weight-bold">Register</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-4">
                            <div class="col-12 text-center">
                                Punya Akun ? <a href="{{ route('login') }}" class="">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    @push('javascript-bottom')
        <script>
            $('#type_of_user').select2({
                multiple: true,
                placeholder: 'Pilih Jenis Akun',
                dropdownParent: $('.register-box')
            });
            $('#id_payment_package').select2({
                placeholder: 'Pilih Paket',
                dropdownParent: $('.register-box')
            });
            $('#type_of_user').val('').trigger('change');
            $('#id_payment_package').val('').trigger('change');
        </script>
    @endpush
@endsection
