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
                <div id="question_box">
                    @include('quiz.play.question')
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

            function backPage() {

                $.ajax({
                    url: $('#url-previous').val(),
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        $('#question_box').html(data);
                    },
                    error: function(xhr) {
                        console.log(
                            xhr); // Melihat informasi error jika terjadi
                        if (xhr.status == 401) {
                            swalError('Sesi Anda Telah Habis');
                            window.location.href =
                                '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                        } else if (xhr.status == 500) {
                            swalError('Terjadi Kesalahan Koneksi');
                        }
                    },
                });

            }

            function navigateToQuestion(element) {
                const url = $(element).data('url'); // Ambil URL dari atribut data-url
                // Jika user yakin, langsung ambil data jawaban yang dipilih
                let selectedAnswer = $('#answer_list input[type="radio"]:checked').val();
                let resultId = $('#result_id').val();
                let questionId = $('#question_id').val();
                let questionNumber = $('#active_question').data('question-number');
                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ url('admin/quiz/answer') }}',
                    type: 'POST',
                    cache: false,
                    data: {
                        _token: token,
                        value: selectedAnswer,
                        resultId: resultId,
                        questionId: questionId,
                        q: questionNumber,
                    },
                    success: function(data) {
                        console.log("Jawaban berhasil disimpan:", data);
                        $.ajax({
                            url: url,
                            type: 'GET',
                            cache: false,
                            success: function(data) {
                                $('#question_box').html(
                                    data); // Replace isi `#question_box` dengan respons
                            },
                            error: function(xhr) {
                                console.error(xhr); // Tampilkan informasi error di console
                                if (xhr.status == 401) {
                                    swalError('Sesi Anda Telah Habis');
                                    window.location.href =
                                        '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                                } else if (xhr.status == 500) {
                                    swalError('Terjadi Kesalahan Koneksi');
                                }
                            },
                        });
                    },
                    error: function(xhr) {
                        console.log("Error AJAX:", xhr); // Melihat error dari server
                        swalError('Gagal mengirim jawaban, silakan coba lagi.');
                    },
                });

            }



            function nextPage() {
                let allowed = false;

                // Pastikan pengguna memilih salah satu jawaban
                $('#answer_list').each(function() {
                    if ($(this).find('input[type="radio"]:checked').length > 0) {
                        allowed = true;
                    }
                });

                if (!allowed) {
                    swalError('Harap Menjawab Pertanyaan Terlebih Dahulu!');
                    return false;
                }

                // Jika user yakin, langsung ambil data jawaban yang dipilih
                let selectedAnswer = $('#answer_list input[type="radio"]:checked').val();
                let resultId = $('#result_id').val();
                let questionId = $('#question_id').val();
                let questionNumber = $('#active_question').data('question-number');
                let token = $('meta[name="csrf-token"]').attr('content');

                // Kirim data jawaban ke server menggunakan AJAX
                $.ajax({
                    url: '{{ url('admin/quiz/answer') }}',
                    type: 'POST',
                    cache: false,
                    data: {
                        _token: token,
                        value: selectedAnswer,
                        resultId: resultId,
                        questionId: questionId,
                        q: questionNumber,
                    },
                    success: function(data) {
                        console.log("Jawaban berhasil disimpan:", data);

                        // Ambil pertanyaan berikutnya
                        $.ajax({
                            url: $('#url-next').val(),
                            type: 'GET',
                            cache: false,
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    console.log('Pertanyaan');
                                    $('#question_box').html(data);
                                } else {
                                    console.log('Tidak ada data pertanyaan baru');
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr); // Melihat informasi error jika terjadi
                                if (xhr.status == 401) {
                                    swalError('Sesi Anda Telah Habis');
                                    window.location.href =
                                        '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                                } else if (xhr.status == 500) {
                                    swalError('Terjadi Kesalahan Koneksi');
                                }
                            },
                        });
                    },
                    error: function(xhr) {
                        console.log("Error AJAX:", xhr); // Melihat error dari server
                        swalError('Gagal mengirim jawaban, silakan coba lagi.');
                    },
                });
            }


            function timeExpired() {
                Swal.fire({
                    title: 'Waktu Quiz Anda Telah Habis!',
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
                        let questionId = $('#question_id').val();
                        let selectedAnswer = $('#answer_list input[type="radio"]:checked').val();

                        console.log("resultId:", resultId);
                        console.log("questionId:", questionId);

                        // Kirim data terakhir ke server
                        $.ajax({
                            url: '{{ url('admin/quiz/finish') }}',
                            type: 'POST',
                            cache: false,
                            data: {
                                _token: token,
                                value: selectedAnswer || '',
                                resultId: resultId,
                                questionId: questionId,
                                q: $('#active_question').data('question-number') || 0,
                            },
                            success: function() {
                                const resultUrl =
                                    `{{ route('admin.quiz.result', ['resultId' => '__RESULT_ID__']) }}`
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
                    title: 'Apakah Anda yakin ingin menyelesaikan quiz ini?',
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
                        let selectedAnswer = $('#answer_list input[type="radio"]:checked').val();
                        let resultId = $('#result_id').val();
                        let questionId = $('#question_id').val();
                        let token = $('meta[name="csrf-token"]').attr('content');

                        // Kirim data terakhir ke server
                        $.ajax({
                            url: '{{ url('admin/quiz/finish') }}',
                            type: 'POST',
                            cache: false,
                            data: {
                                _token: token,
                                value: selectedAnswer,
                                resultId: resultId,
                                questionId: questionId,
                                q: $('#active_question').data('question-number'),
                            },
                            success: function() {
                                const resultUrl =
                                    `{{ route('admin.quiz.result', ['resultId' => '__RESULT_ID__']) }}`
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
