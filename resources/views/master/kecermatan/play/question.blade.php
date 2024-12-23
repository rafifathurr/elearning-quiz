<div class="row">

    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="card-title mb-0 font-weight-bold my-auto">
                    Soal {{ $active_question['question_number'] }}
                </h2>
            </div>
            <div id="active_question" data-question-number="{{ $active_question['question_number'] }}">
                <input type="hidden" name="result_id" id="result_id" value="{{ $result->id }}">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-center">
                                @php
                                    $index_new = 0;
                                @endphp
                                @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                    <div class="p-2 text-center">

                                        <h3 class="font-weight-bold">
                                            {{ chr(substr('000' . ($index_new + 65), -3)) }}
                                        </h3>
                                        <div class="card m-2 px-2 bg-primary text-white">
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ $quiz_answer['answer'] }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $index_new++;
                                    @endphp
                                @endforeach
                            </div>

                            <h3 class="font-weight-bold text-center mt-3">
                                SOAL
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center ">
                                @php
                                    $index_new = 0;
                                    $randomized_answers = $active_question['quiz_answer'];
                                    shuffle($randomized_answers);
                                @endphp


                                @foreach ($randomized_answers as $quiz_answer)
                                    @if ($quiz_answer['is_answer'] == 0)
                                        <div class="p-2 text-center">

                                            <div class="card m-2 px-2 bg-primary text-white">
                                                <div class="card-body">
                                                    <h4 class="font-weight-bold text-center my-auto">
                                                        {{ $quiz_answer['answer'] }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $index_new++;
                                        @endphp
                                    @endif
                                @endforeach
                            </div>



                            <div class="border-top " id="answer_list">
                                <div class="col-lg-12 py-3 mt-4 d-flex flex-wrap justify-content-center col-md-4">
                                    @php
                                        $index_new = 0;
                                    @endphp
                                    @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                        <div class="card my-2 mx-4 px-2 bg-primary text-white" style="cursor: pointer;"
                                            onclick="answerKecermatan(this)">
                                            <input type="hidden" name="answer_list"
                                                value="{{ $quiz_answer['answer'] }}">
                                            <div class="card-body">
                                                <h4 class="font-weight-bold text-center my-auto">
                                                    {{ chr(substr('000' . ($index_new + 65), -3)) }}
                                                </h4>
                                            </div>
                                        </div>
                                        @php
                                            $index_new++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" id="url-next"
                                value="{{ route('kecermatan.getQuestion', ['result' => $result->id]) . '?q=' . $active_question['question_number'] + 1 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
