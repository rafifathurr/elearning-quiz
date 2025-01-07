<div class="row">

    <div class="col-lg-12 col-md-12">
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

                            <div class="d-flex flex-wrap justify-content-center">
                                <h3 class="text-center"></h3>
                                @foreach ($unique_answers as $index => $answer)
                                    <div class="p-2 text-center">
                                        <h3 class="font-weight-bold">
                                            {{ chr(65 + $index) }} <!-- Convert index to A, B, C, etc. -->
                                        </h3>
                                        <div class="card m-2 px-2 bg-white text-dark border">
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ $answer }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <h3 class="font-weight-bold text-center mt-3">
                                SOAL
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                    <div class="p-2 text-center">
                                        <div class="card m-2 px-2 bg-white text-dark border">
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ $quiz_answer['answer'] }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>




                            <div class="border-top " id="answer_list">
                                <div class="col-lg-12 py-3 mt-4 d-flex flex-wrap justify-content-center col-md-4">
                                    @php
                                        $index_new = 0;
                                    @endphp
                                    @foreach ($unique_answers as $index => $answer)
                                        <div class="p-2 text-center">
                                            <div class="card my-2 mx-4 px-2 bg-white text-dark border"
                                                style="cursor: pointer;" onclick="answerKecermatan(this)">
                                                <input type="hidden" name="answer_list" value="{{ $answer }}">
                                                <div class="card-body">
                                                    <h4 class="font-weight-bold text-center my-auto">
                                                        {{ chr(65 + $index) }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
