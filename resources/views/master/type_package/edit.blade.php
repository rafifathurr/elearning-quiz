@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Kategori Paket #{{ $type_package->id }}</h1>
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
                        <form method="post" action="{{ route('master.typePackage.update', [($id = $type_package->id)]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 control-label text-left">Nama Kategori Paket
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" id="name" value="{{ old('name', $type_package->name) }}"
                                            required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="id_parent">Kategori Utama
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <div style="overflow: auto; max-height: 300px;">
                                            <ul style="list-style-type: none; padding-left: 0;">
                                                @foreach ($types as $type)
                                                    @include('master.type_package.type_node', [
                                                        'type' => $type,
                                                        'level' => 0,
                                                        'selectedType' => $type_package->id_parent, // Kirim data yang terpilih
                                                    ])
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <textarea placeholder="Deskripsi" rows="4" name="description" id="description" class="form-control ">{{ old('description', $type_package->description) }}</textarea>
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
    @endpush
@endsection
