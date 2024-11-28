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
                                {{ $active_question['question'] ?? '' }}
                            </p>
                            @if (!is_null($active_question['attachment']))
                                <img src="{{ asset($active_question['attachment']) }}" class="img-fluid"
                                    style="max-height: 14rem;">
                            @endif
                            <div class="mt-3">
                                {!! $active_question['description'] !!}
                            </div>
                            <div class="border-top py-3 mt-4" id="answer_list">
                                @foreach ($active_question['quiz_answer'] as $quiz_answer)
                                    <div class="form-check py-3">
                                        <input class="form-check-input" type="radio" name="answer_list"
                                            class="form-control" value="{{ $quiz_answer['answer'] }}"
                                            @if ($active_question['answered']) checked @endif>
                                        <label class="form-check-label">{{ $quiz_answer['answer'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer py-3">
                            <div
                                class="d-flex  @if ($active_question['question_number'] != 1) justify-content-between @else justify-content-end @endif">
                                @if ($active_question['question_number'] != 1)
                                    <div class="mx-2">
                                        <button onclick="backPage()" class="btn btn-danger"><i
                                                class="fas fa-arrow-left mr-2"></i>Kembali</button>
                                        <input type="hidden" id="url-previous"
                                            value="{{ route('admin.quiz.getQuestion', ['result' => $result->id]) . '?q=' . $active_question['question_number'] - 1 }}">
                                    </div>
                                @endif

                                <p class="mt-2">{{ $active_question['question_number'] }} /
                                    {{ $total_question }}</p>
                                @if ($active_question['question_number'] == $total_question)
                                    <div class="mx-2">
                                        <button onclick="finishQuiz()" class="btn btn-success">Finish<i
                                                class="fas fa-arrow-right ml-2"></i></button>

                                    </div>
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
</div>
