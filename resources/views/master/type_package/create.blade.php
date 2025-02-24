@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- form start -->
                        <form method="post" action="{{ route('master.typePackage.store') }}">
                            @csrf
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="font-weight-bold card-title">Tambah Kategori Paket</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Nama Kategori
                                            Paket
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
                                        <label class="col-md-4 control-label text-left" for="id_parent">Kategori
                                            Utama</label>
                                        <div class="col-md-8 col-sm-12">
                                            <div style="overflow: auto; max-height: 300px;">
                                                <ul style="list-style-type: none; padding-left: 0;">
                                                    @foreach ($types as $type)
                                                        @include('master.type_package.type_node', [
                                                            'type' => $type,
                                                            'level' => 0,
                                                            'selectedType' => $selectedType ?? null, // Tambahkan ini
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
                                            <textarea placeholder="Deskripsi" rows="4" name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card card-maroon">
                                        <div class="card-header">
                                            <h3 class="font-weight-bold card-title">Hak Akses</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="user_id" class="col-md-4 control-label text-left">Pilih Paket
                                                    Manajer
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select class="form-control @error('user_id[]') is-invalid @enderror"
                                                        name="user_id[]" id="user_id"
                                                        data-placeholder="Pilih Paket Manajer" style="width: 100%;">
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('user_id[]')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3 d-flex justify-content-end">
                                        <a href="{{ route('master.typePackage.index') }}" class="btn btn-danger mr-2">
                                            Back</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
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
            $('#user_id').select2({
                multiple: true,
            });
            $('#user_id').val('').trigger('change');
        </script>
    @endpush
@endsection
