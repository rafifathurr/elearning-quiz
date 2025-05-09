@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Edit |
                                    {{ $aspect->name }}</h3>
                            </div>

                            <!-- form start -->
                            <form method="post" action="{{ route('master.aspect.update', [($id = $aspect->id)]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Aspek Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                                name="name" id="name" value="{{ old('name', $aspect->name) }}"
                                                required>
                                            @error('name')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="description" class="col-md-4 control-label text-left">Tipe Aspek
                                            <span class="text-danger">*</span></label>
                                        <div class="col-md-8 col-sm-12">
                                            <select name="type_aspect" id="type_aspect" class="form-control">
                                                <option value="">Pilih Tipe Aspek</option>
                                                <option value="kecerdasan"
                                                    {{ $aspect->type_aspect == 'kecerdasan' ? 'selected' : '' }}>Kecerdasan
                                                </option>
                                                <option value="kepribadian"
                                                    {{ $aspect->type_aspect == 'kepribadian' ? 'selected' : '' }}>
                                                    Kepribadian
                                                </option>
                                            </select>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <textarea id="quiz_summernote" name="description" id="description" class="form-control summernote">{{ old('description', $aspect->description) }}</textarea>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="pt-3 d-flex">
                                        <a href="{{ route('master.aspect.index') }}" class="btn btn-danger mr-2"> Back</a>
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
        @include('js.master.aspect.script')
    @endpush
@endsection
