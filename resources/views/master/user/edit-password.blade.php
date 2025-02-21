@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-lightblue text-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">Ubah Password</h3>
                        </div>
                        <!-- form start -->
                        <form method="post" action="{{ route('my-account.updatePassword', ['id' => $user->id]) }}">
                            @csrf
                            @method('patch')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password" value="{{ old('password') }}"
                                        required>

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
                                        value="{{ old('re_password') }}" required>

                                    @error('re_password')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="pt-3 d-flex">
                                    <a href="{{ route('my-account.show') }}" class="btn btn-danger mr-2"> Back</a>
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
