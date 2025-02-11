@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Tambah Jadwal Kelas</h3>
                            </div>

                            <!-- form start -->
                            <form method="post" action="{{ route('master.dateclass.store') }}">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label for="date_code" class="col-md-4 control-label text-left">Kode Jadwal
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('date_code') is-invalid @enderror"
                                                type="text" name="date_code" id="date_code"
                                                value="{{ old('date_code') }}" placeholder="Kode Jadwal" required>
                                            @error('date_code')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Jadwal Kelas
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                                name="name" id="name" value="{{ old('name') }}"
                                                placeholder="Jadwal Kelas" required>
                                            @error('name')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="pt-3 d-flex">
                                        <a href="{{ route('master.dateclass.index') }}" class="btn btn-danger mr-2">
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
    </div>

    @push('javascript-bottom')
        @include('js.master.dateclass.script')
    @endpush
@endsection
