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

    <body class="hold-transition register-page" style="background-color: #f4f6f9;">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                    </h4>
                    <p class="text-muted">Silakan isi data di bawah untuk membuat akun baru.</p>

                    <form action="{{ route('account.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap"
                                    oninput="this.value = this.value.toUpperCase();" value="{{ old('name') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user text-primary"></span>
                                    </div>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror" placeholder="Username"
                                    value="{{ old('username') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user-circle text-primary"></span>
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
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                    value="{{ old('email') }}" required>
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

                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="Nomor Handphone"
                                    value="{{ old('phone') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-phone-alt text-primary"></span>
                                    </div>
                                </div>
                                @error('phone')
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
                                    <a href="javascript:;" onclick="togglePasswordVisibility('password', 'password-icon')"
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

                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="re_password" id="re_password"
                                    class="form-control border-right-0 @error('re_password') is-invalid @enderror"
                                    placeholder="Konfirmasi Password" required>
                                <div class="input-group-append">
                                    <a href="javascript:;"
                                        onclick="togglePasswordVisibility('re_password', 're-password-icon')"
                                        class="input-group-text bg-transparent border-left-0">
                                        <i class='fas fa-eye text-secondary' style="opacity: 0.5;"
                                            id="re-password-icon"></i>
                                    </a>
                                </div>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock text-primary"></span>
                                    </div>
                                </div>
                                @error('re_password')
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
                                    <i class="fas fa-user-check"></i> Daftar Sekarang
                                </button>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-12 text-center">
                                <p>Sudah punya akun? <a href="{{ route('login') }}"
                                        class="text-primary font-weight-bold">Login di sini</a></p>
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

            document.addEventListener('DOMContentLoaded', function() {
                const password = document.getElementById('password');
                const re_password = document.getElementById('re_password');

                function checkRePassword(event) {
                    var input = event.target;

                    if (input.id === 're_password' && input.value !== password.value) {
                        input.setCustomValidity('Password Tidak Sama.');
                    } else {
                        input.setCustomValidity('');
                    }
                }
                re_password.addEventListener('input', checkRePassword);


                var phone = document.getElementById('phone');

                function validateInput(event) {
                    var input = event.target;
                    // Menghapus karakter non-angka
                    input.value = input.value.replace(/\D/g, '');

                    if (input.id === 'phone' && input.value.length < 10 || input.value.length > 13) {
                        input.setCustomValidity('Nomor HP terdiri dari 10 sampai 13 angka.');
                    } else {
                        input.setCustomValidity('');
                    }
                }
                phone.addEventListener('input', validateInput);
            });

            function togglePasswordVisibility(inputId, iconId) {
                var passwordField = document.getElementById(inputId);
                var icon = document.getElementById(iconId);

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        </script>
    @endpush
@endsection
