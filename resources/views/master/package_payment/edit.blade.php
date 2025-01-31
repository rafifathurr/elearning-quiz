@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Paket Test #{{ $package->id }}</h1>
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
                        <form method="post" action="{{ route('master.package.update', [($id = $package->id)]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 control-label text-left">Paket Test
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" id="name" value="{{ old('name', $package->name) }}"
                                            required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label text-left" for="id_type_package">Tipe Paket <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('id_type_package') is-invalid @enderror"
                                            id="id_type_package" name="id_type_package" required>
                                            <option disabled hidden selected>Pilih Tipe Paket</option>
                                            @foreach ($types as $type)
                                                @if (old('roles', $package->id_type_package) == $type->id)
                                                    <option value="{{ $type->id }}" selected>
                                                        {{ $type->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $type->id }}">{{ $type->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('id_type_package')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class" class="col-md-4 control-label text-left">Jumlah Pertemuan
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('class') is-invalid @enderror" type="number"
                                            name="class" id="class" value="{{ old('class', $package->class) }}">
                                        @error('class')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="price" class="col-md-4 control-label text-left">Harga Paket
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('price') is-invalid @enderror" type="text"
                                            name="price" id="price" value="{{ old('price', $package->price) }}"
                                            required>
                                        @error('price')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="quiz_id" class="col-md-4 control-label text-left">Pilih Test
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="hidden" id="value_quiz"
                                            value="{{ json_encode($package->packageTest->pluck('quiz_id')->toArray()) }}">
                                        <select class="form-control @error('quiz_id[]') is-invalid @enderror"
                                            name="quiz_id[]" id="quiz_id" data-placeholder="Pilih Jenis Test"
                                            style="width: 100%;" required>
                                            @foreach ($quizes as $quiz)
                                                <option value="{{ $quiz->id }}" selected>
                                                    {{ $quiz->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="pt-3 d-flex">
                                    <a href="{{ route('master.package.index') }}" class="btn btn-danger mr-2"> Back</a>
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
            $('#quiz_id').select2({
                multiple: true,
            });
            $('#id_type_package').select2();

            $('#quiz_id').val('').trigger('change');
            $('#quiz_id').val(JSON.parse($('#value_quiz').val())).trigger('change');
        </script>
        @include('js.master.package_payment.script')
    @endpush
@endsection
