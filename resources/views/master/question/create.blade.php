@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Pertanyaan</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('master.question.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="direction_question" class="col-md-4 control-label text-left">
                                        Petunjuk Pertanyaan
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('direction_question') is-invalid @enderror"
                                            type="text" name="direction_question" value="{{ old('direction_question') }}"
                                            required>
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
                                        <input class="form-control @error('question') is-invalid @enderror" type="text"
                                            name="question" value="{{ old('question') }}">
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
                                                    type="text" name="time_duration" value="{{ old('time_duration') }}">
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
                                                    type="text" name="level" value="{{ old('level') }}" required>
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
                                    <label for="type_quiz" class="col-md-4 control-label text-left">Aspek
                                        Pertanyaan
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <select class="form-control @error('type_quiz[]') is-invalid @enderror"
                                            name="type_quiz[]" id="type_quiz" required>
                                            @foreach ($type_quiz as $type_of_quiz)
                                                <option value="{{ $type_of_quiz->id }}">
                                                    {{ $type_of_quiz->name }}
                                                </option>
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
                                    <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
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
                                                name="is_generate_random_answer" id="is_generate_random_answer"
                                                @if (old('is_generate_random_answer')) checked @endif>
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
                                                id="is_random_answer" @if (old('is_random_answer')) checked @endif>
                                            <label class="custom-control-label" for="is_random_answer">Acak
                                                Semua Jawaban</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-gray-light" id="headingTwo">
                                        <h2 class="card-title mb-0 font-weight-bold">
                                            Daftar Jawaban
                                        </h2>
                                    </div>
                                    <div class="card-body">
                                        <div id="question_list"></div>
                                        <div class="form-group border rounded p-5">
                                            <div class="text-center p-2" id="add_question" onclick="appendForm()">
                                                <h4 class="my-auto">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Jawaban
                                                </h4>
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
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('javascript-bottom')
        <script>
            $('#type_quiz').select2({
                multiple: true,
            });

            $('#type_quiz').val('').trigger('change');

            function enabledEvent(element, target) {
                if (element.checked) {
                    $('#'.concat(target)).attr('disabled', false);
                    $('#'.concat(target)).attr('required', true);
                } else {
                    $('#'.concat(target)).attr('required', false);
                    $('#'.concat(target)).attr('disabled', true);
                }
            }

            let answer_increment = 0;

            function appendForm(parentId) { // Gunakan parentId sebagai ID pertanyaan
                answer_increment++;
                $.ajax({
                    url: "{{ route('master.question.append') }}",
                    type: "GET",
                    data: {
                        question: 0,
                        answer: 1,
                        increment: answer_increment,
                        parent: parentId // Gunakan parent ID yang sesuai
                    },
                    success: function(data) {
                        $('#question_list').append(data);
                    },
                    error: function(xhr, status, error) {
                        swalError(error);
                    }
                });
            }


            function remove(target) {
                $('#'.concat(target)).remove();
            }



            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection
