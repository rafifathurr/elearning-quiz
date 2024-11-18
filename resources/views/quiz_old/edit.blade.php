@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $disabled == '' ? 'Ubah' : 'Detail' }} Quiz</h1>
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
                                                Konfigurasi Quiz
                                            </h2>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Judul
                                                    Quiz
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
                                                <label for="type_quiz" class="col-md-4 control-label text-left">Kategori
                                                    Quiz
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select class="form-control @error('type_quiz') is-invalid @enderror"
                                                        name="type_quiz" id="type_quiz" required {{ $disabled }}>
                                                        <option value="">Pilih Kategori Quiz</option>
                                                        @foreach ($type_quiz as $type_of_quiz)
                                                            <option value="{{ $type_of_quiz->id }}"
                                                                @if ((!is_null(old('type_quiz')) && old('type') == $type_of_quiz->id) || $quiz->type_quiz_id == $type_of_quiz->id) selected @endif>
                                                                {{ $type_of_quiz->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('type_quiz')
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
                                                    <input type="hidden" id="value_type_user"
                                                        value="{{ json_encode($quiz->quizTypeUserAccess->pluck('type_user_id')->toArray()) }}">
                                                    <select
                                                        class="form-control @error('quiz_type_user[]') is-invalid @enderror"
                                                        name="quiz_type_user[]" id="quiz_type_user"
                                                        data-placeholder="Pilih Tipe User" style="width: 100%;" required
                                                        {{ $disabled }}>
                                                        @foreach ($type_user as $type_of_user)
                                                            <option value="{{ $type_of_user->id }}" selected>
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
                                                <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <textarea id="quiz_summernote" name="description" id="description" class="form-control summernote" {{ $disabled }}>{{ old('description', $quiz->description) }}</textarea>
                                                    @error('description')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-md-4 control-label text-left">&nbsp;
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            name="is_random_question" id="is_random_question"
                                                            @if (old('is_random_question', $quiz->is_random_question)) checked @endif
                                                            {{ $disabled }}>
                                                        <label class="custom-control-label" for="is_random_question">Acak
                                                            Pertanyaan</label>
                                                    </div>
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
                                                    Waktu Quiz Dibuka
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('open_quiz') is-invalid @enderror"
                                                                type="datetime-local" name="open_quiz" id="open_quiz"
                                                                value="{{ old('open_quiz', $quiz->open_quiz) }}"
                                                                @if (!old('open_quiz', $quiz->open_quiz)) disabled @endif
                                                                {{ $disabled }}>
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
                                                                    onchange="enabledEvent(this, 'open_quiz')"
                                                                    @if (old('open_quiz', $quiz->open_quiz)) checked @endif
                                                                    {{ $disabled }}>
                                                                <label for=""
                                                                    class="font-weight-medium ml-2 my-auto">Enabled</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">
                                                    Waktu Quiz Ditutup
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('close_quiz') is-invalid @enderror"
                                                                type="datetime-local" name="close_quiz" id="close_quiz"
                                                                value="{{ old('close_quiz', $quiz->close_quiz) }}"
                                                                @if (!old('close_quiz', $quiz->close_quiz)) disabled @endif
                                                                {{ $disabled }}>
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
                                                                    onchange="enabledEvent(this, 'close_quiz')"
                                                                    @if (old('close_quiz', $quiz->close_quiz)) checked @endif
                                                                    {{ $disabled }}>
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
                                                Daftar Pertanyaan
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" id="count_question"
                                                value="{{ count($quiz->quizQuestion) }}">
                                            <div id="question_list">
                                                {!! $quiz_question !!}
                                            </div>
                                            @if ($disabled == '')
                                                <div class="form-group border rounded p-5">
                                                    <div class="text-center p-2" id="add_question"
                                                        onclick="appendForm(true, false)">
                                                        <h4 class="my-auto">
                                                            <i class="fas fa-plus mr-2"></i>
                                                            Tambah Pertanyaan
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
            let question_increment = $('#count_question').val();
            let answer_increment = 0;

            $('#quiz_type_user').select2({
                multiple: true,
            });

            $('#quiz_type_user').val('').trigger('change');
            $('#quiz_type_user').val(JSON.parse($('#value_type_user').val())).trigger('change');

            function enabledEvent(element, target) {
                if (element.checked) {
                    $('#'.concat(target)).attr('disabled', false);
                    $('#'.concat(target)).attr('required', true);
                } else {
                    $('#'.concat(target)).attr('required', false);
                    $('#'.concat(target)).attr('disabled', true);
                }
            }

            function appendForm(question, arr_of_answer) {
                if (question) {
                    question_increment++;
                    $.ajax({
                        url: "{{ route('admin.quiz.append') }}",
                        type: "GET",
                        data: {
                            question: question,
                            answer: 0,
                            increment: question_increment,
                        },
                        success: function(data) {
                            $('#question_list').append(data);
                        },
                        error: function(xhr, status, error) {
                            swalError(error);
                        }
                    });
                } else {
                    if (arr_of_answer[0]) {

                        if ($('#question_answer_'.concat(arr_of_answer[2])).val() != undefined) {
                            answer_increment = parseInt($('#question_answer_'.concat(arr_of_answer[2])).val()) + 1;
                            $.ajax({
                                url: "{{ route('admin.quiz.append') }}",
                                type: "GET",
                                data: {
                                    question: 0,
                                    answer: arr_of_answer[0],
                                    parent: arr_of_answer[2],
                                    increment: answer_increment,
                                },
                                success: function(data) {
                                    $('#question_answer_'.concat(arr_of_answer[2])).val(answer_increment);
                                    $('#'.concat(arr_of_answer[1])).append(data);
                                },
                                error: function(xhr, status, error) {
                                    swalError(error);
                                }
                            });
                        } else {
                            answer_increment++;
                            $.ajax({
                                url: "{{ route('admin.quiz.append') }}",
                                type: "GET",
                                data: {
                                    question: 0,
                                    answer: arr_of_answer[0],
                                    parent: arr_of_answer[2],
                                    increment: answer_increment,
                                },
                                success: function(data) {
                                    $('#'.concat(arr_of_answer[1])).append(data);
                                },
                                error: function(xhr, status, error) {
                                    swalError(error);
                                }
                            });
                        }
                    }
                }
            }

            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection