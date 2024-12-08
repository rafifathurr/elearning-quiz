<div class="card" id="answer_form_{{ $increment }}">


    <div class="card-header">
        <h2 class="card-title mt-2">Jawaban {{ $increment }}</h2>

        @if ($disabled == '')
            <div class="dropdown text-black">
                <button class="btn btn-link text-black-50" id="dropdownMenuButtonAnswer" data-toggle="dropdown"><i
                        class="fas fa-cog"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkAnswer">
                    <a class="dropdown-item" onclick="remove('answer_form_{{ $increment }}')"
                        href="#">Hapus</a>
                </div>
            </div>
        @endif
    </div>

    <div class="card-body">
        <!-- Answer Field -->
        <div class="form-group row">
            <label class="col-md-4 control-label text-left">Jawaban </label>
            <div class="col-md-8 col-sm-12">
                <input type="text" name="quiz_answer[{{ $increment }}][answer]"
                    class="form-control @error('quiz_answer.{{ $increment }}.answer') is-invalid @enderror"
                    value="{{ old('quiz_answer.' . $increment . '.answer', $quiz_answer->answer ?? '') }}"
                    {{ $disabled }} />
                @error('quiz_answer.{{ $increment }}.answer')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 control-label text-left">Gambar </label>
            <div class="col-md-8 col-sm-12">
                <input type="hidden" name="quiz_answer[{{ $increment }}][answer_image]"
                    value="{{ old('quiz_answer.' . $increment . '.answer_image', $quiz_answer->answer_image ?? '') }}">
                <input type="file"
                    class="form-control @error('quiz_answer.{{ $increment }}.answer_image') is-invalid @enderror"
                    name="quiz_answer[{{ $increment }}][answer_image]" id="documentInput"
                    accept="image/jpeg,image/jpg,image/png" {{ $disabled }}>
                <p class="text-danger py-1">* .jpg .jpeg .png</p>
                @if (!empty($quiz_answer->answer_image))
                    <label class="m-2">
                        <a href="{{ asset($quiz_answer->answer_image) }}" target="_blank">
                            <i class="fas fa-download mr-1"></i>
                            Gambar Terlampir
                        </a>
                    </label>
                @endif

                @error('quiz_answer.{{ $increment }}.answer_image')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- Point Field -->
        <div class="form-group row">
            <label class="col-md-4 control-label text-left">Point<span class="text-danger ml-1">*</span></label>
            <div class="col-md-8 col-sm-12">
                <input type="number" name="quiz_answer[{{ $increment }}][point]"
                    class="form-control @error('quiz_answer.{{ $increment }}.point') is-invalid @enderror"
                    value="{{ old('quiz_answer.' . $increment . '.point', $quiz_answer->point ?? '') }}" required
                    {{ $disabled }} />
                @error('quiz_answer.{{ $increment }}.point')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- Correct Answer Switch -->
        <div class="form-group row">
            <label class="col-md-4 control-label text-left">Jawaban Benar</label>
            <div class="col-md-8 col-sm-12">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="quiz_answer[{{ $increment }}][is_answer]"
                        class="custom-control-input" id="is_answer_{{ $increment }}"
                        @if (old('quiz_answer.' . $increment . '.is_answer', $quiz_answer->is_answer ?? false)) checked @endif {{ $disabled }} />
                    <label class="custom-control-label" for="is_answer_{{ $increment }}">Benar</label>
                </div>

            </div>
        </div>
    </div>
</div>
