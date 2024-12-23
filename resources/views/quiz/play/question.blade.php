<div class="row">

    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="card-title mb-0 font-weight-bold my-auto">
                    Soal {{ $active_question['question_number'] }}
                </h2>
            </div>
            <div id="active_question" data-question-number="{{ $active_question['question_number'] }}">
                <input type="hidden" name="result_id" id="result_id" value="{{ $result->id }}">
                <input type="hidden" name="question_id" id="question_id" value="{{ $active_question['id'] }}">
                <div class="card-body">
                    <div class="card">
                        <div class="card-header bg-gray-light">
                            <h2 class="card-title mb-0 font-weight-bold my-auto">
                                Petunjuk Soal : {{ $active_question['direction_question'] }}
                            </h2>
                        </div>
                        <div class="card-body">
                            <p>
                                {!! $active_question['question'] ?? '' !!}
                            </p>
                            @if (!is_null($active_question['attachment']))
                                <img src="{{ asset($active_question['attachment']) }}" class="img-fluid"
                                    style="max-height: 14rem;">
                            @endif
                            {{-- @if ($active_question['is_generate_random_answer'])
                                <div class="d-flex flex-wrap justify-content-center">
                                    @php
                                        $index_new = 0;
                                    @endphp
                                    @foreach ($active_question['quiz_answer'] as $quiz_answer)
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
                                        $randomized_answers = $active_question['quiz_answer'];
                                        shuffle($randomized_answers);
                                    @endphp

                                    @foreach ($randomized_answers as $quiz_answer)
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
                            @endif --}}
                            <div class="mt-3">
                                {!! $active_question['description'] !!}
                            </div>
                            <div class="border-top py-3 mt-4" id="answer_list">
                                @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                    <div class="form-check py-3">
                                        <input class="form-check-input" type="radio" name="answer_list"
                                            onchange="answer(this)"
                                            value="{{ $quiz_answer['answer'] ?? $quiz_answer['answer_image'] }}"
                                            @if (
                                                $active_question['answered'] &&
                                                    ($active_question['user_answer'] == $quiz_answer['answer'] ||
                                                        $active_question['user_answer'] == $quiz_answer['answer_image'])) checked @endif>
                                        <label class="form-check-label">
                                            {{ $quiz_answer['answer'] ?? '' }}
                                        </label> <br />
                                        @if (!is_null($quiz_answer['answer_image']))
                                            <img src="{{ asset($quiz_answer['answer_image']) }}" class="img-fluid"
                                                style="max-height: 10rem;">
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="card-footer py-3">
                            <div
                                class="d-flex  @if ($active_question['question_number'] != 1 && $quiz['type_aspect'] == 'kepribadian') justify-content-between @else justify-content-end @endif">
                                @if ($active_question['question_number'] != 1 && $quiz['type_aspect'] == 'kepribadian')
                                    <div class="mx-2">
                                        <button onclick="backPage()" class="btn btn-danger"><i
                                                class="fas fa-arrow-left mr-2"></i>Kembali</button>
                                        <input type="hidden" id="url-previous"
                                            value="{{ route('admin.quiz.getQuestion', ['result' => $result->id]) . '?q=' . $active_question['question_number'] - 1 }}">
                                    </div>
                                @endif
                                @if ($active_question['question_number'] == $total_question)
                                @else
                                    <div class="mx-2">

                                        <button onclick="nextPage()" class="btn btn-primary">Selanjutnya<i
                                                class="fas fa-arrow-right ml-2"></i></button>
                                        <input type="hidden" id="url-next"
                                            value="{{ route('admin.quiz.getQuestion', ['result' => $result->id]) . '?q=' . $active_question['question_number'] + 1 }}">

                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title mb-0 font-weight-bold my-auto">
                    Nomor Soal
                </h2>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-center" style="gap: 8px;">
                    @foreach ($questionList as $quiz_question_list)
                        <div class="card" style="width: 18%;">
                            <div class="card-body d-flex justify-content-center align-items-center  @if ($quiz_question_list['is_active']) bg-primary text-white 
                        @elseif($quiz_question_list['answered']) bg-success text-white 
                        @else bg-light text-dark @endif"
                                @if ($quiz['type_aspect'] == 'kepribadian') onclick="navigateToQuestion(this)"
                        data-url="{{ route('admin.quiz.getQuestion', ['result' => $result->id, 'q' => $quiz_question_list['question_number']]) }}"
                        data-q="{{ $quiz_question_list['question_number'] }}" style="cursor: pointer;" @endif>
                                <h5 class="font-weight-bold text-center mb-0">
                                    {{ $quiz_question_list['question_number'] }}
                                </h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>
