<div class="card" id="question_form_{{ $increment }}">
    <div class="card-header bg-gray-light d-flex justify-content-start" id="headingTwo">
        <h2 class="card-title mb-0 font-weight-bold my-auto">
            Aspek {{ $increment }}
        </h2>
        @isset($quiz_aspect->id)
            <input type="hidden" name="quiz_aspect[{{ $increment }}][id]" value="{{ $quiz_aspect->id }}">
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
            <label for="quiz_aspect[{{ $increment }}][aspect_id]" class="col-md-4 control-label text-left">
                Aspek Pertanyaan
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <select name="quiz_aspect[{{ $increment }}][aspect_id]"
                    id="quiz_aspect[{{ $increment }}][aspect_id]" class="form-control" required>
                    <option value="">Pilih Aspek Pertanyaan</option>
                    @foreach ($aspect_question as $aspect)
                        <option value="{{ $aspect->id }}"
                            {{ old('quiz_aspect.' . $increment . '.aspect_id', $quiz_aspect->aspect_id ?? '') == $aspect->id ? 'selected' : '' }}>
                            {{ $aspect->name }}
                        </option>
                    @endforeach
                </select>

                @error('quiz_aspect[' . $increment . '][aspect_id]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="quiz_aspect[{{ $increment }}][level]" class="col-md-4 control-label text-left">
                Level Pertanyaan
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <select name="quiz_aspect[{{ $increment }}][level]" id="quiz_aspect[{{ $increment }}][level]"
                    class="form-control" required>
                    <option value="">Pilih Level Pertanyaan</option>
                    <option value="1"
                        {{ old('quiz_aspect.' . $increment . '.level', $quiz_aspect->level ?? '') == 1 ? 'selected' : '' }}>
                        Level 1
                    </option>
                    <option value="2"
                        {{ old('quiz_aspect.' . $increment . '.level', $quiz_aspect->level ?? '') == 2 ? 'selected' : '' }}>
                        Level 2
                    </option>
                    <option value="3"
                        {{ old('quiz_aspect.' . $increment . '.level', $quiz_aspect->level ?? '') == 3 ? 'selected' : '' }}>
                        Level 3
                    </option>
                </select>

                @error('quiz_aspect[' . $increment . '][level]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="quiz_aspect[{{ $increment }}][total_question]" class="col-md-4 control-label text-left">
                Jumlah Pertanyaan
                <span class="text-danger ml-1">*</span>
            </label>
            <div class="col-md-8 col-sm-12">
                <input
                    class="form-control @error('quiz_aspect[{{ $increment }}][total_question]') is-invalid @enderror"
                    type="number" min="1" name="quiz_aspect[{{ $increment }}][total_question]"
                    value="{{ old('quiz_aspect.' . $increment . '.total_question', $quiz_aspect->total_question ?? '') }}"
                    {{ $disabled }}>
                @error('quiz_aspect[' . $increment . '][total_question]')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

    </div>
</div>
