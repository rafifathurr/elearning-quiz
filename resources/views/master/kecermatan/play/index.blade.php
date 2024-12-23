@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="p-2">
                                <h2 class="card-title mb-0 font-weight-bold my-auto ml-auto px-3 py-2">
                                    {{ $quiz['name'] }}
                                </h2>
                                <input type="hidden" id="type_aspect" value="{{ $quiz['type_aspect'] }}">
                                <input type="hidden" id="total_question" value="{{ $total_question }}">
                            </div>
                            <div class="d-flex">
                                <div class="p-2">
                                    <div class="mx-2">
                                        <button onclick="finishQuiz()" class="btn btn-success">Selesai<i
                                                class="fas fa-flag ml-2"></i></button>

                                    </div>
                                </div>
                                <div class="p-2">
                                    <h2 class="card-title mb-0 font-weight-bold my-auto ml-auto bg-dark px-3 py-2 rounded">
                                        <input type="hidden" value="{{ $quiz['time_duration'] }}" id="time">
                                        <span id="hour_time">--</span> : <span id="minute_time">--</span> : <span
                                            id="second_time">--</span>
                                    </h2>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div id="question_box">
                    @include('master.kecermatan.play.question')
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
        <script>
            // Ambil waktu dari localStorage jika ada, jika tidak, gunakan nilai dari elemen input.
            let time = localStorage.getItem('remainingTime') ? parseInt(localStorage.getItem('remainingTime')) : parseInt($(
                '#time').val());




            function answerKecermatan(element) {
                let selectedAnswer = $(element).find('input[name="answer_list"]').val();
                let resultId = $('#result_id').val(); // Ambil resultId dari input hidden
                let token = $('meta[name="csrf-token"]').attr('content');
                let questionNumber = $('#active_question').data('question-number');
                let totalQuestion = $('#total_question').val();

                console.log('Current Question Number:', questionNumber);
                console.log('Total Question:', totalQuestion);

                let isLastQuestion = questionNumber == totalQuestion;
                console.log('isLastQuestion:', isLastQuestion);


                $.ajax({
                    url: '{{ url('kecermatan/answer') }}',
                    type: 'POST',
                    cache: false,
                    data: {
                        _token: token,
                        value: selectedAnswer,
                        resultId: resultId,
                        questionNumber: questionNumber,
                    },
                    success: function(data) {
                        console.log("Jawaban berhasil disimpan:", data.value);

                        $('#answer_list .card').removeClass('bg-success').addClass('bg-primary');
                        $(element).removeClass('bg-primary').addClass('bg-success');


                        console.log('Cek apakah ini pertanyaan terakhir...');

                        // jika pertanyaan terakhir
                        if (isLastQuestion) {
                            console.log('Pertanyaan terakhir!');

                            $.ajax({
                                url: '{{ url('kecermatan/finish') }}',
                                type: 'POST',
                                cache: false,
                                data: {
                                    _token: token,
                                    resultId: resultId,
                                    q: $('#active_question').data('question-number') || 0,
                                },
                                success: function() {
                                    const resultUrl =
                                        `{{ route('kecermatan.result', ['resultId' => '__RESULT_ID__']) }}`
                                        .replace('__RESULT_ID__', resultId);
                                    window.location.href = resultUrl;
                                },
                                error: function(xhr) {
                                    console.error("Error AJAX:", xhr);
                                    swalError('Gagal menyelesaikan quiz, silakan coba lagi.');
                                },
                            });
                            return;

                        }
                        console.log('Bukan pertanyaan terakhir.');

                        // Lanjutkan ke pertanyaan berikutnya jika bukan pertanyaan terakhir
                        let questionNumber = $('#active_question').data('question-number');
                        $.ajax({
                            url: $('#url-next').val(),
                            type: 'GET',
                            cache: false,
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    $('#question_box').html(data);
                                } else {
                                    swalError('Tidak ada pertanyaan berikutnya.');
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                if (xhr.status == 401) {
                                    swalError('Sesi Anda telah habis.');
                                    window.location.href =
                                        '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                                } else if (xhr.status == 500) {
                                    swalError('Terjadi kesalahan koneksi.');
                                }
                            },
                        });

                    },
                    error: function(xhr) {
                        console.log("Error AJAX:", xhr);
                        swalError('Gagal mengirim jawaban, silakan coba lagi.');
                    },
                });
            }


            function timeExpired() {
                Swal.fire({
                    title: 'Waktu tes Anda Telah Habis!',
                    icon: 'warning',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        let resultId = $('#result_id').val();


                        // Kirim data terakhir ke server
                        $.ajax({
                            url: '{{ url('kecermatan/finish') }}',
                            type: 'POST',
                            cache: false,
                            data: {
                                _token: token,
                                resultId: resultId,
                                q: $('#active_question').data('question-number') || 0,
                            },
                            success: function() {
                                const resultUrl =
                                    `{{ route('kecermatan.result', ['resultId' => '__RESULT_ID__']) }}`
                                    .replace('__RESULT_ID__', resultId);
                                window.location.href = resultUrl;
                            },
                            error: function(xhr) {
                                console.error("Error AJAX:", xhr);
                                swalError('Gagal menyelesaikan quiz, silakan coba lagi.');
                            },
                        });
                    }
                });
            }




            updateTimestamp();
            let interval = setInterval(updateTimestamp, 1000);

            function updateTimestamp() {

                if (time % 60 > 9) {
                    $('#second_time').html(time % 60);
                } else {
                    $('#second_time').html('0'.concat(time % 60));
                }

                if (time > 0) {

                    if (Math.floor(time / 60) > 0) {
                        if (Math.floor(time / 60) > 9) {
                            $('#minute_time').html(Math.floor(time / 60));
                        } else {
                            $('#minute_time').html('0'.concat(Math.floor(time / 60)))
                        }
                    } else {
                        $('#minute_time').html('00');
                    }

                    if (Math.floor(time / 3600) > 0) {
                        $('#hour_time').html(Math.floor(time / 3600));
                    } else {
                        $('#hour_time').html('00');
                    }

                    // Simpan waktu yang tersisa ke localStorage
                    localStorage.setItem('remainingTime', time);
                    time--;
                } else {
                    clearInterval(interval);
                    timeExpired();
                }
            }

            function finishQuiz() {
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menyelesaikan tes ini?',
                    icon: 'question',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-primary mr-2 mb-3',
                        cancelButton: 'btn btn-danger mb-3',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        let resultId = $('#result_id').val();

                        console.log("resultId:", resultId);

                        // Kirim data terakhir ke server
                        $.ajax({
                            url: '{{ url('kecermatan/finish') }}',
                            type: 'POST',
                            cache: false,
                            data: {
                                _token: token,
                                resultId: resultId,
                                q: $('#active_question').data('question-number') || 0,
                            },
                            success: function() {
                                const resultUrl =
                                    `{{ route('kecermatan.result', ['resultId' => '__RESULT_ID__']) }}`
                                    .replace('__RESULT_ID__', resultId);
                                window.location.href = resultUrl;
                            },
                            error: function(xhr) {
                                console.error("Error AJAX:", xhr);
                                swalError('Gagal menyelesaikan quiz, silakan coba lagi.');
                            },
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
