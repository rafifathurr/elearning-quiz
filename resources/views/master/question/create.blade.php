@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Pertanyaan</h1>
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
                            <form action="{{ route('master.question.store') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Daftar Pertanyaan
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div id="question_list"></div>
                                            <div class="form-group border rounded p-5">
                                                <div class="text-center p-2" id="add_question"
                                                    onclick="appendForm(true, false)">
                                                    <h4 class="my-auto">
                                                        <i class="fas fa-plus mr-2"></i>
                                                        Tambah Pertanyaan
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
            let question_increment = 0;
            let answer_increment = 0;

            function initializeSelect2ForQuizType(increment) {
                $('#quiz_question\\[' + increment + '\\]\\[type_quiz\\]').select2({
                    placeholder: "Pilih Kategori Quiz",
                    allowClear: true,
                    multiple: true,
                    width: '100%'
                }).val(null).trigger('change'); // Pastikan tidak ada yang terpilih
            }


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
                        url: "{{ route('master.question.append') }}",
                        type: "GET",
                        data: {
                            question: question,
                            answer: 0,
                            increment: question_increment,
                        },
                        success: function(data) {
                            $('#question_list').append(data);
                            initializeSelect2ForQuizType(question_increment);
                        },
                        error: function(xhr, status, error) {
                            swalError(error);
                        }
                    });
                } else {
                    if (arr_of_answer[0]) {
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
