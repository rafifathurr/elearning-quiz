<div class="card" id="question_form_{{ $increment }}">
    <div class="card-header bg-gray-light d-flex justify-content-start" id="headingTwo">
        <h2 class="card-title mb-0 font-weight-bold my-auto">
            Pertanyaan {{ $increment }}
        </h2>
        @isset($quiz_question->id)
            <input type="hidden" name="quiz_question[{{ $increment }}][id]" value="{{ $quiz_question->id }}">
        @endisset
        @if ($disabled == '')
            <div class="dropdown text-black">
                <button class="btn btn-link text-black-50" id="dropdownMenuButtonQuestion" data-toggle="dropdown"><i
                        class="fas fa-cog"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkQuestion">
                    <a class="dropdown-item" onclick="remove('question_form_{{ $increment }}')"
                        href="#">Hapus</a>
                </div>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="quiz_question[{{ $increment }}][direction_question]"
                class="col-md-4 control-label text-left">
                Petunjuk Pertanyaan
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <input
                    class="form-control @error('quiz_question[{{ $increment }}][direction_question]') is-invalid @enderror"
                    type="text" name="quiz_question[{{ $increment }}][direction_question]"
                    value="{{ old('quiz_question[' . $increment . '][direction_question]', $quiz_question->direction_question ?? '') }}"
                    required {{ $disabled }}>
                @error('quiz_question[' . $increment . '][direction_question]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="quiz_question[{{ $increment }}][question]" class="col-md-4 control-label text-left">
                Pertanyaan
            </label>
            <div class="col-md-8 col-sm-12">
                <input class="form-control @error('quiz_question[{{ $increment }}][question]') is-invalid @enderror"
                    type="text" name="quiz_question[{{ $increment }}][question]"
                    value="{{ old('quiz_question[' . $increment . '][question]', $quiz_question->question ?? '') }}"
                    {{ $disabled }}>
                @error('quiz_question[' . $increment . '][question]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-md-4 control-label text-left">Durasi
                Waktu (Detik)
            </label>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <input
                            class="form-control @error('quiz_question[{{ $increment }}][time_duration]') is-invalid @enderror"
                            type="text" name="quiz_question[{{ $increment }}][time_duration]"
                            value="{{ old('quiz_question[' . $increment . '][time_duration]', $quiz_question->time_duration ?? '') }}"
                            {{ $disabled }}>
                        @error('quiz_question[' . $increment . '][time_duration]')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="quiz_question[{{ $increment }}][level]" class="col-md-4 control-label text-left">
                Level
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <input
                            class="form-control @error('quiz_question[{{ $increment }}][level]') is-invalid @enderror"
                            type="text" name="quiz_question[{{ $increment }}][level]"
                            value="{{ old('quiz_question[' . $increment . '][level]', $quiz_question->level ?? '') }}"
                            {{ $disabled }} required>
                        @error('quiz_question[' . $increment . '][level]')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="quiz_question[{{ $increment }}][description]"
                class="col-md-4 control-label text-left">Deskripsi
            </label>
            <div class="col-md-8 col-sm-12">
                <textarea id="quiz_question_{{ $increment }}" name="quiz_question[{{ $increment }}][description]"
                    class="form-control summernote" {{ $disabled }}>{{ old('quiz_question[' . $increment . '][description]', $quiz_question->description ?? '') }}</textarea>
                @error('quiz_question[' . $increment . '][description]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="quiz_question[{{ $increment }}][type_quiz]" class="col-md-4 control-label text-left">Aspek
                Pertanyaan
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <select
                    class="form-control @error('quiz_question[{{ $increment }}][type_quiz][]') is-invalid @enderror"
                    name="quiz_question[{{ $increment }}][type_quiz][]"
                    id="quiz_question[{{ $increment }}][type_quiz]" {{ $disabled }} required>
                    @foreach ($type_quiz as $type_of_quiz)
                        <option value="{{ $type_of_quiz->id }}"
                            {{ old('quiz_question[' . $increment . '][type_quiz]', $quiz_question->type_quiz ?? '') == $type_of_quiz->id ? 'selected' : '' }}>
                            {{ $type_of_quiz->name }}
                        </option>
                    @endforeach
                </select>
                @error('quiz_question[' . $increment . '][type_quiz]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>


        <div class="form-group row">
            <label for="" class="col-md-4 control-label text-left">&nbsp;
            </label>
            <div class="col-md-8 col-sm-12">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input"
                        name="quiz_question[{{ $increment }}][is_generate_random_answer]"
                        id="is_generate_random_answer_{{ $increment }}"
                        @if (old(
                                'quiz_question[' . $increment . '][is_generate_random_answer]',
                                $quiz_question->is_generate_random_answer ?? '')) checked @endif {{ $disabled }}>
                    <label class="custom-control-label" for="is_generate_random_answer_{{ $increment }}">Acak
                        Jawaban Selain
                        Jawaban Benar
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-4 control-label text-left">&nbsp;
            </label>
            <div class="col-md-8 col-sm-12">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input"
                        name="quiz_question[{{ $increment }}][is_random_answer]"
                        id="is_random_answer_{{ $increment }}" @if (old('quiz_question[' . $increment . '][is_random_answer]', $quiz_question->is_random_answer ?? '')) checked @endif
                        {{ $disabled }}>
                    <label class="custom-control-label" for="is_random_answer_{{ $increment }}">Acak
                        Semua Jawaban</label>
                </div>
            </div>
        </div>
        <div id="answer_list_{{ $increment }}">
            @isset($quiz_answer)
                @foreach ($quiz_answer as $quiz_answer_list)
                    {!! $quiz_answer_list !!}
                @endforeach
                <input type="hidden" id="question_answer_{{ $increment }}" value="{{ count($quiz_answer) }}">
            @endisset
        </div>
        @if ($disabled == '')
            <div class="form-group border rounded p-3">
                <div class="text-center" id="add_answer"
                    onclick="appendForm(false, [true, 'answer_list_{{ $increment }}', {{ $increment }}])">
                    <h5 class="my-auto">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Jawaban
                    </h5>
                </div>
            </div>
        @endif
    </div>
</div>
