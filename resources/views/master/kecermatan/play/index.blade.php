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
            // Ambil waktu dari localStorage jika ada, jika tidak, gunakan nilai dari elemen input.
            let time = localStorage.getItem('remainingTime') ? parseInt(localStorage.getItem('remainingTime')) : parseInt($(
                '#time').val());


            function answerKecermatan(element) {
                if (durasi <= 1) {
                    console.log("Waktu hampir habis, jawaban tidak dikirim.");
                    return;
                }
                let selectedAnswer = $(element).find('input[name="answer_list"]').val();
                let resultId = $('#result_id').val();
                let token = $('meta[name="csrf-token"]').attr('content');
                let questionNumber = $('#active_question').data('question-number');
                let totalQuestion = $('#total_question').val();
                let currentCombination = $('#current_combination').val();

                $.ajax({
                    url: '{{ url('kecermatan/answer') }}',
                    type: 'POST',
                    data: {
                        _token: token,
                        value: selectedAnswer,
                        resultId: resultId,
                        questionNumber: questionNumber,
                        currentCombination: currentCombination,
                    },
                    success: function(data) {
                        // Status jawaban berhasil
                        console.log("Jawaban berhasil disimpan:", data.value);

                        // Update tampilan dan mark kombinasi yang dipilih
                        $('#answer_list .card').removeClass('bg-secondary').addClass('bg-white');
                        $(element).removeClass('bg-white').addClass('bg-secondary');

                        // Lanjutkan ke pertanyaan berikutnya jika bukan pertanyaan terakhir
                        goToNextQuestionOrCombination();
                    },
                    error: function(xhr) {
                        console.error("Error AJAX:", xhr);
                        swalError('Gagal mengirim jawaban, silakan coba lagi.');
                    },
                });
            }

            function goToNextQuestionOrCombination() {
                // Lakukan pengecekan apakah waktu sudah habis
                if (time <= 0) {
                    moveNextCombination();
                } else {
                    // Lanjutkan ke pertanyaan berikutnya
                    $.ajax({
                        url: $('#url-next').val(),
                        type: 'GET',
                        success: function(data) {
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
                                window.location.href = '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                            }
                        }
                    });
                }
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
                if (time > 0) {
                    // Hitung detik
                    let seconds = time % 60;
                    $('#second_time').html(seconds > 9 ? seconds : '0' + seconds);

                    // Hitung menit
                    let minutes = Math.floor((time % 3600) / 60);
                    $('#minute_time').html(minutes > 9 ? minutes : '0' + minutes);

                    // Hitung jam
                    let hours = Math.floor(time / 3600);
                    $('#hour_time').html(hours > 9 ? hours : '0' + hours);

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

            let durasi = localStorage.getItem('waktuSisa') ? parseInt(localStorage.getItem('waktuSisa')) : parseInt($('#durasi')
                .val());

            let kombinasiSekarang = localStorage.getItem('kombinasi') ? localStorage.getItem('kombinasi') : $(
                '#current_combination').val();

            const displayCombination = kombinasiSekarang.replace(/([a-zA-Z]+)(\d+)/, 'Kolom $2');

            $('#current').html(displayCombination);

            updateWaktu();
            let intervalWaktu = setInterval(updateWaktu, 1000);

            function updateWaktu() {
                if (durasi > 0) {
                    let seconds = durasi % 60;
                    $('#detik').html(seconds > 9 ? seconds : '0' + seconds);

                    let minutes = Math.floor((durasi % 3600) / 60);

                    if (durasi < 60) {
                        $('#menit').hide();
                        $('#batas').hide();
                    } else {
                        $('#batas').show();
                        $('#menit').show();
                        $('#menit').html(minutes > 9 ? minutes : '0' + minutes);
                    }

                    let hours = Math.floor(durasi / 3600);
                    $('#jam').html(hours > 9 ? hours : '0' + hours);

                    localStorage.setItem('waktuSisa', durasi);

                    if (durasi === 1) {
                        $('.card.bg-white').addClass('disabled').css('pointer-events', 'none');
                    }

                    durasi--;
                } else {
                    clearInterval(intervalWaktu);
                    localStorage.removeItem('waktuSisa');

                    Swal.fire({
                        icon: 'info',
                        title: 'Waktu habis!',
                        text: 'Berpindah ke kombinasi berikutnya.',
                        toast: false,
                        position: 'center',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        timer: 1000,
                        timerProgressBar: true,
                    }).then(() => {
                        moveNextCombination();
                    });
                }
            }

            function moveNextCombination() {
                let durasiKombinasi = @json($durasi_kombinasi);
                let soalData = @json($soal_data);
                let currentCombination = $('#current_combination').val();

                // Dapatkan urutan kombinasi
                let combinations = Object.keys(durasiKombinasi);
                let currentIndex = combinations.indexOf(currentCombination);
                let nextCombination = combinations[currentIndex + 1]; // Kombinasi berikutnya
                let isLastCombination = currentIndex === combinations.length - 1; // Apakah ini kombinasi terakhir?

                let lastQuestionInCurrentCombination = soalData.filter(
                    soal => soal.nama_kombinasi === currentCombination
                ).pop();

                let nextQuestionNumber = 1;
                if (lastQuestionInCurrentCombination) {
                    nextQuestionNumber = lastQuestionInCurrentCombination.order + 1;
                }

                // Jika kombinasi terakhir selesai
                if (isLastCombination && nextQuestionNumber > lastQuestionInCurrentCombination.order) {
                    let token = $('meta[name="csrf-token"]').attr('content');
                    let resultId = $('#result_id').val();

                    $.ajax({
                        url: '{{ url('kecermatan/finish') }}',
                        type: 'POST',
                        cache: false,
                        data: {
                            _token: token,
                            resultId: resultId,
                            q: nextQuestionNumber,
                        },
                        success: function() {
                            const resultUrl = `{{ route('kecermatan.result', ['resultId' => '__RESULT_ID__']) }}`
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

                // Menuju kombinasi berikutnya
                if (nextCombination) {
                    let firstQuestionInNextCombination = soalData.find(
                        soal => soal.nama_kombinasi === nextCombination
                    );

                    if (firstQuestionInNextCombination) {
                        let nextCombinationUrl = '{{ route('kecermatan.getQuestion', ['result' => $result->id]) }}';
                        nextCombinationUrl += `?q=${firstQuestionInNextCombination.order}`;
                        nextCombinationUrl += '&durasi_kombinasi=' + encodeURIComponent(JSON.stringify(durasiKombinasi));

                        $.ajax({
                            url: nextCombinationUrl,
                            type: 'GET',
                            cache: false,
                            success: function(data) {
                                if (data) {
                                    $('#question_box').html(data);

                                    let newDuration = parseInt($('#durasi').val());
                                    if (!isNaN(newDuration)) {
                                        durasi = newDuration;
                                        clearInterval(intervalWaktu);
                                        intervalWaktu = setInterval(updateWaktu, 1000);
                                    }

                                    const displayNextCombination = nextCombination.replace(/([a-zA-Z]+)(\d+)/,
                                        'Kolom $2');
                                    $('#current').html(displayNextCombination);

                                    localStorage.removeItem('waktuSisa');
                                    localStorage.setItem('kombinasi', nextCombination);

                                }
                            },
                            error: function(xhr) {
                                if (xhr.status == 401) {
                                    swalError('Sesi Anda telah habis.');
                                    window.location.href =
                                        '{{ route('admin.quiz.start', ['quiz' => $quiz['id']]) }}';
                                } else if (xhr.status == 500) {
                                    swalError('Terjadi kesalahan koneksi.');
                                }
                            },
                        });
                    }
                }
            }
        </script>
    @endpush
@endsection
