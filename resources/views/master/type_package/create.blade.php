@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Kategori Paket</h1>
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
                        <form method="post" action="{{ route('master.typePackage.store') }}">
                            @csrf
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 control-label text-left">Nama Kategori Paket
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" id="name" value="{{ old('name') }}"
                                            placeholder="Kategori Paket" required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="id_parent">Kategori Utama
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('id_parent') is-invalid @enderror" id="id_parent"
                                            name="id_parent">
                                            <option value="0" hidden selected>Pilih Kategori Utama</option>
                                            @foreach ($types as $type)
                                                @if (!is_null(old('id_parent')) && old('id_parent') == $type->id)
                                                    <option value="{{ $type->id }}" selected>
                                                        {{ $type->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $type->id }}">{{ $type->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('id_parent')
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
                                        <textarea placeholder="Deskripsi" rows="4" name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="pt-3 d-flex justify-content-end">
                                    <a href="{{ route('master.typePackage.index') }}" class="btn btn-danger mr-2"> Back</a>
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
        @include('js.master.type_package.script')
        <script>
            $('#id_parent').select2();
        </script>
    @endpush
@endsection
