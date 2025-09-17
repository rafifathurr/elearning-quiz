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
                                        <input type="hidden"
                                            value="{{ isset($remaining_time) && !is_null($remaining_time) ? $remaining_time : $quiz['time_duration'] }}"
                                            id="time">
                                        <span id="hour_time">--</span> : <span id="minute_time">--</span> : <span
                                            id="second_time">--</span>
                                    </h2>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-1" id="waktu">
                        <h2 class="card-title mb-0 font-weight-bold my-auto ml-auto px-3 py-2" id="current">
                        </h2>
                        <h2 class="card-title mb-0 font-weight-bold my-auto ml-auto bg-dark px-3 py-2 rounded">
                            <span id="menit">--</span> <span id="batas">:</span> <span id="detik">--</span>
                        </h2>
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
            $(function() {
                const token = $('meta[name="csrf-token"]').attr('content');
                const resultId = $('#result_id').val();
                const quizId = '{{ $quiz['id'] }}';
                let time = parseInt(localStorage.getItem('remainingTime')) || parseInt($('#time').val());
                let durasi = parseInt(localStorage.getItem('waktuSisa')) || parseInt($('#durasi').val());
                let currentCombination = localStorage.getItem('kombinasi') || $('#current_combination').val();
                const durasiKombinasi = @json($durasi_kombinasi);
                const soalData = @json($soal_data);

                // Helper umum AJAX
                function ajaxRequest(url, method, data, onSuccess) {
                    $.ajax({
                        url,
                        type: method,
                        data,
                        cache: false,
                        success: onSuccess,
                        error: xhr => {
                            console.error("Error AJAX:", xhr);
                            if (xhr.status === 401) {
                                swalError('Sesi Anda telah habis.');
                                window.location.href =
                                    `{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}`;
                            } else {
                                swalError('Terjadi kesalahan, silakan coba lagi.');
                            }
                        }
                    });
                }

                // Helper redirect ke hasil
                function goToResult() {
                    const resultUrl = `{{ route('kecermatan.result', ['resultId' => '__RESULT_ID__']) }}`.replace(
                        '__RESULT_ID__', resultId);
                    window.location.href = resultUrl;
                }

                // Kirim jawaban
                window.answerKecermatan = function(element) {
                    if (durasi <= 1) return console.log("Waktu hampir habis, jawaban tidak dikirim.");

                    const selectedAnswer = $(element).find('input[name="answer_list"]').val();
                    const questionNumber = $('#active_question').data('question-number');
                    const currentCombination = $('#current_combination').val();

                    ajaxRequest('{{ url('kecermatan/answer') }}', 'POST', {
                        _token: token,
                        value: selectedAnswer,
                        resultId,
                        questionNumber,
                        currentCombination
                    }, data => {
                        console.log("Jawaban berhasil disimpan:", data.value);
                        $('#answer_list .card').removeClass('bg-secondary').addClass('bg-white');
                        $(element).removeClass('bg-white').addClass('bg-secondary');
                        goToNextQuestionOrCombination();
                    });
                };

                // Pindah ke pertanyaan berikutnya / kombinasi berikutnya
                function goToNextQuestionOrCombination() {
                    if (time <= 0) return moveNextCombination();
                    ajaxRequest($('#url-next').val(), 'GET', {}, data => {
                        if (data) $('#question_box').html(data);
                        else swalError('Tidak ada pertanyaan berikutnya.');
                    });
                }

                // Waktu habis total
                function timeExpired() {
                    Swal.fire({
                        title: 'Waktu tes Anda Telah Habis!',
                        icon: 'warning',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    ajaxRequest('{{ url('kecermatan/finish') }}', 'POST', {
                        _token: token,
                        resultId,
                        q: $('#active_question').data('question-number') || 0
                    }, goToResult);
                }

                // Tombol finish
                window.finishQuiz = function() {
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin menyelesaikan tes ini?',
                        icon: 'question',
                        showCancelButton: true,
                        allowOutsideClick: false,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        customClass: {
                            confirmButton: 'btn btn-primary mr-2 mb-3',
                            cancelButton: 'btn btn-danger mb-3'
                        },
                        buttonsStyling: false
                    }).then(res => {
                        if (res.isConfirmed) ajaxRequest('{{ url('kecermatan/finish') }}', 'POST', {
                            _token: token,
                            resultId,
                            q: $('#active_question').data('question-number') || 0
                        }, goToResult);
                    });
                };

                // Timer utama
                function updateTimer() {
                    if (time > 0) {
                        $('#hour_time').text(String(Math.floor(time / 3600)).padStart(2, '0'));
                        $('#minute_time').text(String(Math.floor((time % 3600) / 60)).padStart(2, '0'));
                        $('#second_time').text(String(time % 60).padStart(2, '0'));
                        localStorage.setItem('remainingTime', time--);
                    } else {
                        clearInterval(timerInterval);
                        timeExpired();
                    }
                }
                let timerInterval = setInterval(updateTimer, 1000);
                updateTimer();

                // Timer kombinasi
                function updateDurasi() {
                    if (durasi > 0) {
                        $('#jam').text(String(Math.floor(durasi / 3600)).padStart(2, '0'));
                        let minutes = Math.floor((durasi % 3600) / 60);
                        $('#menit').toggle(durasi >= 60).text(String(minutes).padStart(2, '0'));
                        $('#batas').toggle(durasi >= 60);
                        $('#detik').text(String(durasi % 60).padStart(2, '0'));
                        localStorage.setItem('waktuSisa', durasi);
                        if (durasi === 1) $('.card.bg-white').addClass('disabled').css('pointer-events', 'none');
                        durasi--;
                    } else {
                        clearInterval(durasiInterval);
                        localStorage.removeItem('waktuSisa');
                        Swal.fire({
                            icon: 'info',
                            title: 'Waktu habis!',
                            text: 'Berpindah ke kombinasi berikutnya.',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(moveNextCombination);
                    }
                }
                let durasiInterval = setInterval(updateDurasi, 1000);
                updateDurasi();

                // Fungsi pindah kombinasi
                function moveNextCombination() {
                    let combinations = Object.keys(durasiKombinasi);
                    let currentIndex = combinations.indexOf(currentCombination);
                    let nextCombination = combinations[currentIndex + 1];
                    let isLastCombination = currentIndex === combinations.length - 1;

                    let lastQuestion = soalData.filter(s => s.nama_kombinasi === currentCombination).pop();
                    let nextQuestionNumber = lastQuestion ? lastQuestion.order + 1 : 1;

                    if (isLastCombination && nextQuestionNumber > lastQuestion.order) {
                        return ajaxRequest('{{ url('kecermatan/finish') }}', 'POST', {
                            _token: token,
                            resultId,
                            q: nextQuestionNumber
                        }, goToResult);
                    }

                    if (nextCombination) {
                        let firstQuestion = soalData.find(s => s.nama_kombinasi === nextCombination);
                        if (!firstQuestion) return;

                        let nextUrl = '{{ route('kecermatan.getQuestion', ['result' => $result->id]) }}' +
                            `?q=${firstQuestion.order}&durasi_kombinasi=${encodeURIComponent(JSON.stringify(durasiKombinasi))}`;

                        ajaxRequest(nextUrl, 'GET', {}, data => {
                            $('#question_box').html(data);
                            let newDuration = parseInt($('#durasi').val());
                            if (!isNaN(newDuration)) {
                                durasi = newDuration;
                                clearInterval(durasiInterval);
                                durasiInterval = setInterval(updateDurasi, 1000);
                            }
                            $('#current').text(nextCombination.replace(/([a-zA-Z]+)(\d+)/, 'Kolom $2'));
                            localStorage.setItem('kombinasi', nextCombination);
                            localStorage.removeItem('waktuSisa');
                            currentCombination = nextCombination;
                        });
                    }
                }

                // Tampilkan kombinasi sekarang
                $('#current').text(currentCombination.replace(/([a-zA-Z]+)(\d+)/, 'Kolom $2'));
            });
        </script>
    @endpush
@endsection
