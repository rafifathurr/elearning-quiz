@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $disabled == '' ? 'Ubah' : 'Detail' }} Test</h1>
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
                            <form action="{{ route('admin.quiz.update', ['quiz' => $quiz->id]) }}" method="post">
                                @csrf
                                @method('patch')
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
                                                        value="{{ old('name', $quiz->name) }}" required {{ $disabled }}>
                                                    @error('name')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="type_aspect" class="col-md-4 control-label text-left">Tipe Aspek
                                                    <span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select name="type_aspect" id="type_aspect" class="form-control"
                                                        disabled>
                                                        <option value="">Pilih Tipe Aspek</option>
                                                        <option value="kecerdasan"
                                                            {{ $quiz->type_aspect == 'kecerdasan' ? 'selected' : '' }}>
                                                            Kecerdasan</option>
                                                        <option value="kepribadian"
                                                            {{ $quiz->type_aspect == 'kepribadian' ? 'selected' : '' }}>
                                                            Kepribadian</option>
                                                        <option value="kecermatan"
                                                            {{ $quiz->type_aspect == 'kecermatan' ? 'selected' : '' }}>
                                                            Kecermatan</option>
                                                    </select>
                                                    <input type="hidden" name="type_aspect"
                                                        value="{{ $quiz->type_aspect }}">
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
                                                                name="time_duration"value="{{ old('time_duration', $quiz->time_duration) }}"
                                                                required {{ $disabled }}>
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
                                                Daftar Aspek Test
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" id="count_aspect" value="{{ count($quiz->quizAspect) }}">
                                            <div id="aspect_list">
                                                {!! $quiz_aspect !!}
                                            </div>
                                            @if ($disabled == '')
                                                <div class="form-group border rounded p-5">
                                                    <div class="text-center p-2" id="add_question"
                                                        onclick="appendForm(true, false)">
                                                        <h4 class="my-auto">
                                                            <i class="fas fa-plus mr-2"></i>
                                                            Tambah Aspek
                                                        </h4>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Kembali</a>
                                    @if ($disabled == '')
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>
                                    @endif
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
            let aspect_increment = $('#count_aspect').val();

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
