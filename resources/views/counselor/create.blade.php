@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buka Kelas</h1>
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
                        <form method="post" action="{{ route('class.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="package_id">Paket <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('package_id') is-invalid @enderror"
                                            id="package_id" name="package_id" required>
                                            <option value="" selected>Pilih Paket</option>
                                            @foreach ($packages as $package)
                                                @if (!is_null(old('package_id')) && old('package_id') == $package->id)
                                                    <option value="{{ $package->id }}" selected>
                                                        {{ $package->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $package->id }}">{{ $package->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('package_id')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="pt-3 ">
                                    <div class="d-flex justify-content-end mt-3">

                                        <a href="{{ route('class.index') }}" class="btn btn-danger mr-2">
                                            Kembali</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
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
            $('#package_id').select2();;
        </script>
        @include('js.master.user.script')
    @endpush
@endsection
