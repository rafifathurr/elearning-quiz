@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Test</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="{{ route('admin.quiz.store') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header bg-gray-light">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Konfigurasi Test
                                            </h2>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Judul
                                                    Test
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input class="form-control @error('name') is-invalid @enderror"
                                                        type="text" name="name" id="name"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="quiz_type_user" class="col-md-4 control-label text-left">Hak
                                                    Akses
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select
                                                        class="form-control @error('quiz_type_user[]') is-invalid @enderror"
                                                        name="quiz_type_user[]" id="quiz_type_user"
                                                        data-placeholder="Pilih Tipe User" style="width: 100%;" required>
                                                        @foreach ($type_user as $type_of_user)
                                                            <option value="{{ $type_of_user->id }}">
                                                                {{ $type_of_user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('quiz_type_user[]')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Tipe Aspek
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select name="type_aspect" id="type_aspect" class="form-control"
                                                        required>
                                                        <option value="">Pilih Tipe Aspek</option>
                                                        <option value="kecerdasan">Kecerdasan</option>
                                                        <option value="kepribadian">Kepribadian</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Konfigurasi Waktu
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="open_quiz" class="col-md-4 control-label text-left">
                                                    Waktu Test Dibuka
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('open_quiz') is-invalid @enderror"
                                                                type="datetime-local" name="open_quiz" id="open_quiz"
                                                                value="{{ old('open_quiz') }}" disabled>
                                                            @error('open_quiz')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2 my-auto">
                                                            <div class="d-flex">
                                                                <input type="checkbox" name="open_quiz_stat"
                                                                    value="{{ old('open_quiz_stat') }}" id="open_quiz_stat"
                                                                    onchange="enabledEvent(this, 'open_quiz')">
                                                                <label for=""
                                                                    class="font-weight-medium ml-2 my-auto">Enabled</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">
                                                    Waktu Test Ditutup
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('close_quiz') is-invalid @enderror"
                                                                type="datetime-local" name="close_quiz" id="close_quiz"
                                                                value="{{ old('close_quiz') }}" disabled>
                                                            @error('close_quiz')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2 my-auto">
                                                            <div class="d-flex">
                                                                <input type="checkbox" name="close_quiz_stat"
                                                                    value="{{ old('close_quiz_stat') }}"
                                                                    id="close_quiz_stat"
                                                                    onchange="enabledEvent(this, 'close_quiz')">
                                                                <label for=""
                                                                    class="font-weight-medium ml-2 my-auto">Enabled</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Durasi
                                                    Waktu (Detik)
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-4 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('time_duration') is-invalid @enderror"
                                                                type="number"
                                                                name="time_duration"value="{{ old('time_duration') }}"
                                                                required>
                                                            @error('time_duration')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Daftar Aspek
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div id="aspect_list"></div>
                                            <div class="form-group border rounded p-5">
                                                <div class="text-center p-2" id="add_question"
                                                    onclick="appendForm(true, false)">
                                                    <h4 class="my-auto">
                                                        <i class="fas fa-plus mr-2"></i>
                                                        Tambah Aspek
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Kembali</a>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
        <script>
            let aspect_increment = 0;

            $('#quiz_type_user').select2({
                multiple: true,
            });

            $('#quiz_type_user').val('').trigger('change');

            function enabledEvent(element, target) {
                if (element.checked) {
                    $('#'.concat(target)).attr('disabled', false);
                    $('#'.concat(target)).attr('required', true);
                } else {
                    $('#'.concat(target)).attr('required', false);
                    $('#'.concat(target)).attr('disabled', true);
                }
            }

            function appendForm(aspect_quiz) {
                aspect_increment++;
                const type_aspect = $('#type_aspect').val();
                $.ajax({
                    url: "{{ route('admin.quiz.append') }}",
                    type: "GET",
                    data: {
                        aspect_quiz: aspect_quiz,
                        increment: aspect_increment,
                        type_aspect: type_aspect,
                    },
                    success: function(data) {
                        $('#aspect_list').append(data);
                    },
                    error: function(xhr, status, error) {
                        swalError(error);
                    }
                });

            }

            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection
