@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah User</h1>
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
                        <form action="{{ route('master.user.update', ['id' => $user->id]) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" placeholder="Username"
                                        value="{{ old('username', $user->username) }}">

                                    @error('username')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type_of_user">Tipe User <span class="text-danger">*</span></label>
                                    <input type="hidden" id="value_type_user"
                                        value="{{ json_encode($user->userTypeAccess->pluck('type_user_id')->toArray()) }}">
                                    <select class="form-control @error('type_of_user[]') is-invalid @enderror"
                                        name="type_of_user[]" id="type_of_user" data-placeholder="Pilih Tipe User"
                                        style="width: 100%;" required {{ $disabled }}>
                                        @foreach ($type_user as $type_of_user)
                                            <option value="{{ $type_of_user->id }}" selected>
                                                {{ $type_of_user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_of_user[]')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Nama"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="roles">Role <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('roles') is-invalid @enderror"
                                        id="roles" name="roles" required>
                                        <option disabled hidden selected>Pilih Peran</option>
                                        @foreach ($roles as $role)
                                            @if (old('roles', $user->getRoleNames()[0]) == $role->name)
                                                <option value="{{ $role->name }}" selected>{{ $role->name }}
                                                </option>
                                            @else
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="Phone"
                                        value="{{ old('phone', $user->phone) }}">

                                    @error('phone')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password"
                                        value="{{ old('password') }}">

                                    @error('password')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="re_password">Re Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('re_password') is-invalid @enderror"
                                        id="re_password" name="re_password" placeholder="Re Password"
                                        value="{{ old('re_password') }}">

                                    @error('re_password')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="pt-3 d-flex">
                                    <a href="{{ route('master.user.index') }}" class="btn btn-danger mr-2"> Back</a>
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

            $('#type_of_user').val('').trigger('change');
            $('#type_of_user').val(JSON.parse($('#value_type_user').val())).trigger('change');
        </script>
        @include('js.master.user.script')
    @endpush
@endsection
