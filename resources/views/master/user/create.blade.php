@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Akun</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card ">

                        <!-- form start -->
                        <form method="post" action="{{ route('master.user.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="username" class="col-md-4 control-label text-left">Username
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('username') is-invalid @enderror" type="text"
                                            name="username" id="username" value="{{ old('username') }}"
                                            placeholder="Username" required>
                                        @error('username')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="type_of_user">Tipe User <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('type_of_user[]') is-invalid @enderror"
                                            name="type_of_user[]" id="type_of_user" data-placeholder="Pilih Tipe User"
                                            style="width: 100%;">
                                            @foreach ($type_user as $type)
                                                <option value="{{ $type->id }}">
                                                    {{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_of_user[]')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="name">Nama <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Nama" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="email">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="roles">Role <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('roles') is-invalid @enderror" id="roles"
                                            name="roles" required>
                                            <option disabled hidden selected>Pilih Peran</option>
                                            @foreach ($roles as $role)
                                                @if (!is_null(old('roles')) && old('roles') == $role->name)
                                                    <option value="{{ $role->name }}" selected>
                                                        {{ $role->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $role->name }}">{{ $role->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="phone">Phone
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}">

                                        @error('phone')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="id_payment_package">Paket
                                        Pembayaran
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <select name="id_payment_package" id="id_payment_package"
                                            class="form-control @error('id_payment_package')
                                            is-invalid
                                        @enderror">
                                            <option disabled hidden selected>Pilih Paket Pembayaran</option>
                                            @foreach ($payment_packages as $payment)
                                                @if (!is_null(old('id_payment_package')) && old('id_payment_package') == $payment->id)
                                                    <option value="{{ $payment->id }}" selected>
                                                        {{ $payment->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('id_payment_package')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="password">Password
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Password" value="{{ old('password') }}">

                                        @error('password')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="re_password">Re
                                        Password <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="password"
                                            class="form-control @error('re_password') is-invalid @enderror"
                                            id="re_password" name="re_password" placeholder="Re Password"
                                            value="{{ old('re_password') }}">

                                        @error('re_password')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-3 d-flex">
                                    <a href="{{ route('master.user.index') }}" class="btn btn-danger mr-2">
                                        Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        <script>
            $('#type_of_user').select2({
                multiple: true,
            });
            $('#roles').select2();
            $('#id_payment_package').select2();

            $('#type_of_user').val('').trigger('change');
        </script>
        @include('js.master.user.script')
    @endpush
@endsection
