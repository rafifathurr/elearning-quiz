@extends('layouts.main')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title mb-0 font-weight-bold my-auto">
                                    Nomor Soal
                                </h2>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-wrap justify-content-between justify-content-sm-center">
                                    @foreach ($quiz['quiz_question'] as $quiz_question_list)
                                        <div class="p-0">
                                            <div
                                                class="card m-2 px-3 @if ($quiz_question_list['is_active'] == true) bg-primary text-white @elseif($quiz_question_list['answered'] == true) bg-gray text-white @endif ">
                                                <div class="card-body">
                                                    <h5 class="font-weight-bold text-center my-auto">
                                                        {{ $quiz_question_list['question_number'] }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h2 class="card-title mb-0 font-weight-bold my-auto">
                                    Soal {{ $quiz_question['question_number'] }}
                                </h2>
                                <h2 class="card-title mb-0 font-weight-bold my-auto ml-auto bg-dark px-3 py-2 rounded">
                                    -- : -- : --
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header bg-gray-light">
                                        <h2 class="card-title mb-0 font-weight-bold my-auto">
                                            Petunjuk Soal : {{ $quiz_question['direction_question'] }}
                                        </h2>
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            {{ $quiz_question['question'] ?? '' }}
                                        </p>
                                        @if ($quiz_question['is_generate_random_answer'])
                                            <div class="d-flex flex-wrap justify-content-center">
                                                @php
                                                    $index_new = 0;
                                                @endphp
                                                @foreach ($quiz_question['quiz_answer'] as $quiz_answer)
                                                    <div class="p-2 text-center">
                                                        <h3>
                                                            {{ $quiz_answer['answer'] }}
                                                        </h3>
                                                        <div class="card m-2 px-2 bg-primary text-white">
                                                            <div class="card-body">
                                                                <h5 class="font-weight-bold text-center my-auto">
                                                                    {{ chr(substr('000' . ($index_new + 65), -3)) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $index_new++;
                                                    @endphp
                                                @endforeach
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-center mt-3">
                                                @php
                                                    $index_new = 0;
                                                @endphp
                                                @foreach ($quiz_question['quiz_answer'] as $quiz_answer)
                                                    @if ($quiz_answer['is_answer'] == 0)
                                                        <div class="p-2 text-center">
                                                            <h3>
                                                                {{ $quiz_answer['answer'] }}
                                                            </h3>
                                                            <div class="card m-2 px-2 bg-primary text-white">
                                                                <div class="card-body">
                                                                    <h5 class="font-weight-bold text-center my-auto">
                                                                        {{ chr(substr('000' . ($index_new + 65), -3)) }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $index_new++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            {!! $quiz_question['description'] !!}
                                        </div>
                                        <div class="border-top py-3 mt-4" id="answer_list">
                                            @foreach ($quiz_question['quiz_answer'] as $quiz_answer)
                                                <div class="form-check py-3">
                                                    <input class="form-check-input" type="radio" name="answer_list"
                                                        onchange="answer(this, $(this).val(), {{ $quiz_question['question_number'] }})"
                                                        class="form-control" value="{{ $quiz_answer['answer'] }}"
                                                        @if ($quiz_answer['answered'] == true) checked @endif>
                                                    <label class="form-check-label">{{ $quiz_answer['answer'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="card-footer py-3">
                                        <div
                                            class="d-flex  @if ($quiz_question['question_number'] != 1) justify-content-between @else justify-content-end @endif">
                                            @if ($quiz_question['question_number'] != 1)
                                                <div class="mx-2">
                                                    <a href="{{ route('admin.quiz.play', ['quiz' => $quiz['id']]) . '?q=' . $quiz_question['question_number'] - 1 }}"
                                                        class="btn btn-danger"><i
                                                            class="fas fa-arrow-left mr-2"></i>Kembali</a>
                                                </div>
                                            @endif
                                            @if ($quiz_question['question_number'] == $quiz['total_question'])
                                                <div class="mx-2">
                                                    <form
                                                        action="{{ route('admin.quiz.finish', ['quiz' => $quiz['id']]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('patch')
                                                        <button type="submit" class="btn btn-success">Selesai<i
                                                                class="fas fa-check ml-2"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="mx-2">
                                                    <a onclick="nextPage()" class="btn btn-primary">Selanjutnya<i
                                                            class="fas fa-arrow-right ml-2"></i></a>
                                                    <input type="hidden" id="url-next"
                                                        value="{{ route('admin.quiz.play', ['quiz' => $quiz['id']]) . '?q=' . $quiz_question['question_number'] + 1 }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            $("form").submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin Submit Quiz?',
                    icon: 'question',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-primary mr-2 mb-3',
                        cancelButton: 'btn btn-danger mb-3',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalProcess();
                        $('form').unbind('submit').submit();
                    }
                })
            });

            function nextPage(evt) {

                let allowed = false;
                $('#answer_list').each(function() {
                    if ($(this).find('input[type="radio"]:checked').length > 0) {
                        allowed = true;
                    }
                });

                if (!allowed) {
                    swalError('Harap Menjawab Pertanyaan Terlebih Dahulu!')
                    return false;
                } else {
                    window.location.href = $('#url-next').val();
                }

            }

            function answer(element, value, question_number) {
                if (element.checked) {
                    let token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{ url('admin/quiz/answer') }}',
                        type: 'POST',
                        cache: false,
                        data: {
                            _token: token,
                            value: value,
                            q: question_number,
                        },
                        success: function(data) {},
                        error: function(xhr, error, message) {
                            if (xhr.status == 401) {
                                swalError('Sesi Anda Telah Habis');
                                window.location.href = '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}'
                            }

                            if (xhr.status == 500) {
                                swalError('Terjadi Kesalahan Koneksi');
                            }
                        }
                    });
                }
            }
        </script>
    @endpush
@endsection
