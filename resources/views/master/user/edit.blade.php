@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            @if (Auth::user()->hasRole('admin'))
                                <h3 class="card-title font-weight-bold">Edit
                                    {{ $user->hasRole('admin') ? 'User' : 'Account' }}
                                    |
                                    {{ $user->name }}</h3>
                            @else
                                <h3 class="card-title font-weight-bold">Ubah Password</h3>
                            @endif
                        </div>
                        <!-- form start -->
                        <form method="post"
                            @if (Auth::user()->hasRole('admin')) action="{{ route('master.user.update', ['id' => $user->id]) }}" @else action="{{ route('my-account.update', ['id' => $user->id]) }}" @endif>
                            @csrf
                            @method('patch')
                            <div class="card-body">
                                @if (Auth::user()->hasRole('admin'))
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
                                        <label for="name">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Nama"
                                            oninput="this.value = this.value.toUpperCase();"
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
                                        <label for="roles">Pilih Role
                                            <span class="text-danger ml-1">*</span>
                                        </label>

                                        <input type="hidden" id="value_role"
                                            value="{{ json_encode($user->getRoleNames()) }}">

                                        <select class="form-control @error('roles[]') is-invalid @enderror" name="roles[]"
                                            id="roles" multiple="multiple" data-placeholder="Pilih Role"
                                            style="width: 100%;" required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
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
                                @endif
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password" value="{{ old('password') }}"
                                        @unless (auth()->user()->hasRole('admin')) required @endunless>

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
                                        value="{{ old('re_password') }}"
                                        @unless (auth()->user()->hasRole('admin')) required @endunless>

                                    @error('re_password')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="pt-3 d-flex">
                                    @if (Auth::user()->hasRole('admin'))
                                        <a href="{{ route('master.user.index') }}" class="btn btn-danger mr-2"> Back</a>
                                    @else
                                        <a href="{{ route('my-account.show') }}" class="btn btn-danger mr-2"> Back</a>
                                    @endif
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
            $('#roles').select2({
                multiple: true,
            });

            let selectedRoles = JSON.parse($('#value_role').val());
            $('#roles').val(selectedRoles).trigger('change');
        </script>
        @include('js.master.user.script')
    @endpush
@endsection
