@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $disabled == '' ? 'Ubah' : 'Detail' }} Pertanyaan</h1>
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
                            <div class="card-header bg-gray-light" id="headingTwo">
                                <h2 class="card-title mb-0 font-weight-bold">
                                    Pertanyaan Nomor #{{ $quiz_question->id }}
                                </h2>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('master.question.update', ['id' => $quiz_question->id]) }}"
                                    method="post">
                                    @csrf
                                    @method('patch')
                                    <div class="form-group row">
                                        <label for="direction_question" class="col-md-4 control-label text-left">
                                            Petunjuk Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('direction_question') is-invalid @enderror"
                                                type="text" name="direction_question"
                                                value="{{ old('direction_question', $quiz_question->direction_question ?? '') }}"
                                                required {{ $disabled }}>
                                            @error('direction_question')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="question" class="col-md-4 control-label text-left">
                                            Pertanyaan
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('question') is-invalid @enderror"
                                                type="text" name="question"
                                                value="{{ old('question', $quiz_question->question ?? '') }}"
                                                {{ $disabled }}>
                                            @error('question')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Durasi
                                            Waktu (Detik)
                                        </label>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input class="form-control @error('time_duration') is-invalid @enderror"
                                                        type="text" name="time_duration"
                                                        value="{{ old('time_duration', $quiz_question->time_duration ?? '') }}"
                                                        {{ $disabled }}>
                                                    @error('time_duration')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="level" class="col-md-4 control-label text-left">
                                            Level
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input class="form-control @error('level') is-invalid @enderror"
                                                        type="text" name="level"
                                                        value="{{ old('level', $quiz_question->level ?? '') }}"
                                                        {{ $disabled }} required>
                                                    @error('level')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <textarea id="description" name="description" class="form-control summernote" {{ $disabled }}>{{ old('description', $quiz_question->description ?? '') }}</textarea>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="type_quiz" class="col-md-4 control-label text-left">Aspek
                                            Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input type="hidden" id="value_type_quiz"
                                                value="{{ json_encode($quiz_question->questionTypeQuiz->pluck('type_quiz_id')->toArray()) }}">
                                            <select class="form-control @error('type_quiz[]') is-invalid @enderror"
                                                name="type_quiz[]" id="type_quiz" data-placeholder="Pilih Tipe User"
                                                style="width: 100%;" required {{ $disabled }}>
                                                @foreach ($type_quiz as $type_of_quiz)
                                                    <option value="{{ $type_of_quiz->id }}" selected>
                                                        {{ $type_of_quiz->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_quiz[]')
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
                                                    name="is_generate_random_answer" id="is_generate_random_answer"
                                                    @if (old('is_generate_random_answer', $quiz_question->is_generate_random_answer ?? '')) checked @endif {{ $disabled }}>
                                                <label class="custom-control-label" for="is_generate_random_answer">Acak
                                                    Jawaban Selain
                                                    Jawaban Benar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 control-label text-left">&nbsp;
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="is_random_answer"
                                                    id="is_random_answer" @if (old('is_random_answer', $quiz_question->is_random_answer ?? '')) checked @endif
                                                    {{ $disabled }}>
                                                <label class="custom-control-label" for="is_random_answer">Acak
                                                    Semua Jawaban</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="answer_list">
                                        @isset($quiz_answer)
                                            @foreach ($quiz_answer as $quiz_answer_list)
                                                {!! $quiz_answer_list !!}
                                            @endforeach
                                            <input type="hidden" id="question_answer" value="{{ count($quiz_answer) }}">
                                        @endisset
                                    </div>
                                    @if ($disabled == '')
                                        <div class="form-group border rounded p-3">
                                            <div class="text-center" id="add_answer"
                                                onclick="appendForm(false, [true, 'answer_list'])">
                                                <h5 class="my-auto">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Jawaban
                                                </h5>
                                            </div>
                                        </div>
                                    @endif
                                </form>
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
            let answer_increment = 0;

            $('#type_quiz').select2({
                multiple: true,
            });

            $('#type_quiz').val('').trigger('change');
            $('#type_quiz').val(JSON.parse($('#value_type_quiz').val())).trigger('change');

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
                if (arr_of_answer[0]) {
                    if ($('#question_answer_'.concat(arr_of_answer[2])).val() !== undefined) {
                        answer_increment = parseInt($('#question_answer_'.concat(arr_of_answer[2])).val()) + 1;
                        $.ajax({
                            url: "{{ route('master.question.append') }}",
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
                            url: "{{ route('master.question.append') }}",
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


            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection
