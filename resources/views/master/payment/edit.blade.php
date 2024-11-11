@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Paket Pembayaran</h1>
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
                        <form method="post" action="{{ route('master.payment.update', [($id = $payment->id)]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 control-label text-left">Nama Paket Pembayaran
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" id="name" value="{{ old('name', $payment->name) }}"
                                            required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <textarea id="quiz_summernote" name="description" id="description" class="form-control summernote">{{ old('description', $payment->description) }}</textarea>
                                        @error('description')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="price" class="col-md-4 control-label text-left">Harga
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('price') is-invalid @enderror" type="text"
                                            name="price" id="price" value="{{ old('price', $payment->price) }}"
                                            required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="price" class="col-md-4 control-label text-left">Akses Kuota
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('quota_access') is-invalid @enderror"
                                            type="text" name="quota_access" id="quota_access"
                                            value="{{ old('quota_access', $payment->quota_access) }}" required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="pt-3 d-flex">
                                    <a href="{{ route('master.payment.index') }}" class="btn btn-danger mr-2"> Back</a>
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
        @include('js.master.payment.script')
    @endpush
@endsection
