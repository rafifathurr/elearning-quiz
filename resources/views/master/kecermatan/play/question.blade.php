<style>
    .card-container,
    .question-container {
        display: flex;
        justify-content: center;
        flex-wrap: nowrap;
        gap: 15px;
        overflow-x: auto;
        padding: 5px;
    }

    .card-container .card {
        flex: 1 1 auto;
        max-width: 18%;
        display: flex;
        padding: 15px;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .question-container .card {
        flex: 1 1 auto;
        max-width: 18%;
        display: flex;
        padding: 15px;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        margin-top: 40px;
    }

    /* Label di luar kartu */
    .card-label {
        position: absolute;
        top: -30px;
        /* Menempatkan label di luar kartu dengan sedikit jarak */
        left: 50%;
        transform: translateX(-50%);
        font-size: 18px;
        font-weight: bold;
        z-index: 1;
        background-color: white;
        padding: 0 5px;
    }

    /* Card body styling */
    .card-container .card-body,
    .question-container .card-body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        padding: 0;
    }

    /* Responsif */
    @media (min-width: 540px) {

        .card-container,
        .question-container {
            gap: 20px;
        }

        .card-container .card,
        .question-container .card {
            max-width: 25%;
            padding: 20px;
            margin-top: 40px;
            /* Memberikan ruang untuk label */
        }

        .card-label {
            position: absolute;
            top: -40px;
            /* Menyesuaikan posisi label untuk ukuran lebih besar */
            left: 50%;
            transform: translateX(-50%);
            font-size: 25px;
            font-weight: bold;
            z-index: 1;
            background-color: white;
            padding: 0 5px;
        }
    }

    @media (min-width: 1024px) {

        .card-container,
        .question-container {
            gap: 30px;
        }

        .card-container .card,
        .question-container .card {
            max-width: 30%;
            padding: 25px;
            margin-top: 50px;
            /* Memberikan lebih banyak ruang untuk label */
        }

        .card-label {
            position: absolute;
            top: -45px;
            /* Menyesuaikan posisi label untuk ukuran lebih besar */
            left: 50%;
            transform: translateX(-50%);
            font-size: 30px;
            font-weight: bold;
            z-index: 1;
            background-color: white;
            padding: 0 5px;
        }
    }
</style>
<div class="row">

    <div class="col-12">
        <div class="card">
            <div class="p-2">
                <h2>
                    <input type="hidden" value="{{ $durasi_kombinasi[$active_question['nama_kombinasi'] ?? ''] ?? 0 }}"
                        id="durasi">
                </h2>
            </div>
            <div class="card-header d-flex justify-content-between">
                <h2 class="card-title mb-0 font-weight-bold my-auto">
                    Soal {{ $active_question['display_time'] }}
                </h2>
            </div>
            <div id="active_question" data-question-number="{{ $active_question['question_number'] }}">
                <input type="hidden" name="result_id" id="result_id" value="{{ $result->id }}">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-center ">
                                <div class="question-container">
                                    @foreach ($unique_answers as $index => $answer)
                                        <div class="card bg-white text-dark border">
                                            <h3 class="font-weight-bold card-label bg-white">
                                                {{ chr(65 + $index) }} <!-- Convert index to A, B, C, etc. -->
                                            </h3>
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ $answer }}
                                                </h4>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <h3 class="font-weight-bold text-center mt-3">
                                SOAL
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center">
                                <div class="card-container">
                                    @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                        <div class="card text-center bg-white text-dark border">
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ $quiz_answer['answer'] }}
                                                </h4>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>




                            <div class="border-top " id="answer_list">
                                <div class="py-3 mt-4 d-flex flex-wrap justify-content-center">
                                    @php
                                        $index_new = 0;
                                    @endphp
                                    <div class="card-container">
                                        @foreach ($unique_answers as $index => $answer)
                                            <div class="card  text-center bg-white text-dark border"
                                                style="cursor: pointer;" onclick="answerKecermatan(this)">
                                                <input type="hidden" name="answer_list" value="{{ $answer }}">
                                                <div class="card-body">
                                                    <h4 class="font-weight-bold text-center my-auto">
                                                        {{ chr(65 + $index) }}
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="url-next"
                                value="{{ route('kecermatan.getQuestion', ['result' => $result->id]) . '?q=' . $active_question['question_number'] + 1 }}">

                            <input type="hidden" id="combination-next"
                                value="{{ route('kecermatan.getQuestion', ['result' => $result->id]) . '?q=' . ($active_question['question_number'] + 1) }}">

                            <input type="hidden" id="current_combination" name="current_combination"
                                value="{{ $currentCombination }}">


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
