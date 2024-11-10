<div class="card" id="answer_form_{{ $parent }}_{{ $increment }}">
    <div class="card-header bg-gray-light d-flex justify-content-start" id="headingTwo">
        <h2 class="card-title mb-0 font-weight-bold my-auto">
            Jawaban {{ $increment }} Soal {{ $parent }}
        </h2>
        @isset($quiz_answer->id)
            <input type="hidden" name="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][id]"
                value="{{ $quiz_answer->id }}">
        @endisset
        @if ($disabled == '')
            <div class="dropdown text-black">
                <button class="btn btn-link text-black-50" id="dropdownMenuButtonAnswer" data-toggle="dropdown"><i
                        class="fas fa-cog"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkAnswer">
                    <a class="dropdown-item" onclick="remove('answer_form_{{ $parent }}_{{ $increment }}')"
                        href="#">Hapus</a>
                </div>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][answer]"
                class="col-md-4 control-label text-left">
                Jawaban
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <input
                    class="form-control @error('quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][answer]') is-invalid @enderror"
                    type="text" name="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][answer]"
                    value="{{ old('quiz_question[' . $parent . '][quiz_answer][' . $increment . '][answer]', $quiz_answer->answer ?? '') }}"
                    required {{ $disabled }}>
                @error('quiz_question[' . $parent . '][quiz_answer][' . $increment . '][answer]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][answer]"
                class="col-md-4 control-label text-left">
                Point
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <input
                            class="form-control @error('quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][point]') is-invalid @enderror"
                            type="number"
                            name="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][point]"
                            value="{{ old('quiz_question[' . $parent . '][quiz_answer][' . $increment . '][point]', $quiz_answer->point ?? '') }}"
                            value="0" required {{ $disabled }}>
                        @error('quiz_question[' . $parent . '][quiz_answer][' . $increment . '][point]')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-4 control-label text-left">&nbsp;
            </label>
            <div class="col-md-8 col-sm-12">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input"
                        id="is_random_answer_{{ $parent }}_{{ $increment }}"
                        name="quiz_question[{{ $parent }}][quiz_answer][{{ $increment }}][is_answer]"
                        @if (old('quiz_question[' . $parent . '][quiz_answer][' . $increment . '][is_answer]', $quiz_answer->is_answer ?? '')) checked @endif {{ $disabled }}>
                    <label class="custom-control-label"
                        for="is_random_answer_{{ $parent }}_{{ $increment }}">Jawaban Benar</label>
                </div>
            </div>
        </div>
    </div>
</div>
