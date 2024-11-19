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
                        // Hapus waktu dari localStorage saat quiz selesai
                        localStorage.removeItem('remainingTime');
                        $('form').unbind('submit').submit();
                    }
                })
            });

            function backPage() {

                $.ajax({
                    url: $('#url-previous').val(),
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        $('#question_box').html(data);
                    },
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

            function nextPage() {

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
                    $.ajax({
                        url: $('#url-next').val(),
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            $('#question_box').html(data);
                        },
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
                    finishQuiz();
                }
            }

            function finishQuiz() {
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
                        // Hapus waktu dari localStorage saat quiz selesai
                        localStorage.removeItem('remainingTime');
                        $('#finish-form').unbind('submit').submit();
                    }
                })
            }
        </script>
    @endpush
@endsection
