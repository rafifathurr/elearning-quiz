@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $disabled == '' ? 'Ubah' : 'Detail' }} Pertanyaan</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-gray-light" id="headingTwo">
                                <h2 class="card-title mb-0 font-weight-bold">
                                    Pertanyaan Nomor #{{ $quiz_question->id }}
                                </h2>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('master.question.update', ['id' => $quiz_question->id]) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <div class="form-group row">
                                        <label for="direction_question" class="col-md-4 control-label text-left">
                                            Petunjuk Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('direction_question') is-invalid @enderror"
                                                type="text" name="direction_question"
                                                value="{{ old('direction_question', $quiz_question->direction_question ?? '') }}"
                                                required {{ $disabled }}>
                                            @error('direction_question')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="question" class="col-md-4 control-label text-left">
                                            Pertanyaan
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('question') is-invalid @enderror"
                                                type="text" name="question"
                                                value="{{ old('question', $quiz_question->question ?? '') }}"
                                                {{ $disabled }}>
                                            @error('question')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="attachment" class="col-md-4 control-label text-left">Gambar</label>

                                        @if ($disabled == '')
                                            <div class="col-md-8 col-sm-12">
                                                <input type="file" class="form-control" name="attachment"
                                                    id="documentInput" accept="image/jpeg,image/jpg,image/png">
                                                @if (!is_null($quiz_question->attachment))
                                                    <label class="m-2">
                                                        <a href="{{ asset($quiz_question->attachment) }}" target="_blank">
                                                            <i class="fas fa-download mr-1"></i>
                                                            Gambar Terlampir
                                                        </a>
                                                    </label>
                                                @endif
                                            </div>
                                        @else
                                            @if (!is_null($quiz_question->attachment))
                                                <img src="{{ asset($quiz_question->attachment) }}" class="img-fluid"
                                                    style="max-height: 18rem;">
                                            @endif
                                        @endif

                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Durasi
                                            Waktu (Detik)
                                        </label>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input class="form-control @error('time_duration') is-invalid @enderror"
                                                        type="text" name="time_duration"
                                                        value="{{ old('time_duration', $quiz_question->time_duration ?? '') }}"
                                                        {{ $disabled }}>
                                                    @error('time_duration')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label for="level" class="col-md-4 control-label text-left">
                                            Level
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            @if ($disabled == '')
                                                <!-- Checkbox untuk memilih semua level -->
                                                <div class="form-check">
                                                    <input class="form-check-input" name="all_level" id="all_level"
                                                        type="checkbox" {{ $quiz_question->level == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label">Pilih Semua Level</label>
                                                </div>
                                                <div class="form-group ml-4">
                                                    <!-- Level 1 Checkbox -->
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="level[]" id="level1"
                                                            value="1" type="checkbox"
                                                            {{ in_array(1, explode('|', $quiz_question->level)) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Level 1</label>
                                                    </div>
                                                    <!-- Level 2 Checkbox -->
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="level[]" id="level2"
                                                            value="2" type="checkbox"
                                                            {{ in_array(2, explode('|', $quiz_question->level)) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Level 2</label>
                                                    </div>
                                                    <!-- Level 3 Checkbox -->
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="level[]" id="level3"
                                                            value="3" type="checkbox"
                                                            {{ in_array(3, explode('|', $quiz_question->level)) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Level 3</label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <ul>
                                                        @php
                                                            $selectedLevels = explode('|', $quiz_question->level);
                                                        @endphp
                                                        <!-- Level 1 -->
                                                        @if (in_array(1, $selectedLevels))
                                                            <li>Level 1</li>
                                                        @endif
                                                        <!-- Level 2 -->
                                                        @if (in_array(2, $selectedLevels))
                                                            <li>Level 2</li>
                                                        @endif
                                                        <!-- Level 3 -->
                                                        @if (in_array(3, $selectedLevels))
                                                            <li>Level 3</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif

                                            @error('level')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="aspect" class="col-md-4 control-label text-left">Aspek Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            @if ($disabled == '')
                                                <div class="form-check">
                                                    <input class="form-check-input" name="all_aspect" id="all_aspect"
                                                        type="checkbox"
                                                        {{ $quiz_question->aspect == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label">Pilih Semua Aspek</label>
                                                </div>
                                                <div class="form-group ml-4">
                                                    @foreach ($aspects as $aspect)
                                                        <?php $isChecked = in_array($aspect->id, explode('|', $quiz_question->aspect)); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="aspect[]"
                                                                id="aspect{{ $aspect->id }}"
                                                                value="{{ $aspect->id }}" type="checkbox"
                                                                {{ $isChecked ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $aspect->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <ul>
                                                        @php
                                                            $selectedAspects = explode('|', $quiz_question->aspect);
                                                        @endphp
                                                        @foreach ($aspects as $aspect)
                                                            @if (in_array($aspect->id, $selectedAspects))
                                                                <li>{{ $aspect->name }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @error('aspect')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <textarea id="description" name="description" class="form-control summernote" {{ $disabled }}>{{ old('description', $quiz_question->description ?? '') }}</textarea>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="form-group row">
                                        <label for="" class="col-md-4 control-label text-left">&nbsp;
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    name="is_generate_random_answer" id="is_generate_random_answer"
                                                    @if (old('is_generate_random_answer', $quiz_question->is_generate_random_answer ?? '')) checked @endif {{ $disabled }}>
                                                <label class="custom-control-label" for="is_generate_random_answer">Acak
                                                    Jawaban Selain
                                                    Jawaban Benar
                                                </label>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 control-label text-left">&nbsp;
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    name="is_random_answer" id="is_random_answer"
                                                    @if (old('is_random_answer', $quiz_question->is_random_answer ?? '')) checked @endif {{ $disabled }}>
                                                <label class="custom-control-label" for="is_random_answer">Acak
                                                    Semua Jawaban</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="answer_list">
                                        @isset($quiz_answer)
                                            @foreach ($quiz_answer as $quiz_answer_list)
                                                {!! $quiz_answer_list !!}
                                            @endforeach
                                            <input type="hidden" id="question_answer" value="{{ count($quiz_answer) }}">
                                        @endisset
                                    </div>
                                    @if ($disabled == '')
                                        <div class="form-group border rounded p-3">
                                            <div class="text-center" id="add_answer"
                                                onclick="appendForm(false, [true, 'answer_list'])">
                                                <h5 class="my-auto">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Jawaban
                                                </h5>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Kembali</a>
                                        <button type="submit" class="btn btn-primary" {{ $disabled }}>
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Untuk Level
                const allLevelCheckbox = document.getElementById('all_level');
                const levelCheckboxes = document.querySelectorAll('[id^="level"]');

                // Fungsi untuk handle checked dan disabled state saat halaman dimuat
                function updateLevelCheckboxes() {
                    if (allLevelCheckbox.checked) {
                        levelCheckboxes.forEach(checkbox => {
                            checkbox.disabled = true;
                            checkbox.checked = false;
                        });
                    } else {
                        levelCheckboxes.forEach(checkbox => {
                            checkbox.disabled = false;
                        });
                    }
                }

                // Jalankan fungsi saat halaman pertama kali dimuat
                updateLevelCheckboxes();

                // Event listener untuk perubahan pada checkbox "Pilih Semua Level"
                allLevelCheckbox.addEventListener('change', updateLevelCheckboxes);

                // Untuk Aspect
                const allAspectCheckbox = document.getElementById('all_aspect');
                const aspectCheckboxes = document.querySelectorAll('[id^="aspect"]');

                // Fungsi untuk handle checked dan disabled state saat halaman dimuat
                function updateAspectCheckboxes() {
                    if (allAspectCheckbox.checked) {
                        aspectCheckboxes.forEach(checkbox => {
                            checkbox.disabled = true;
                            checkbox.checked = false;
                        });
                    } else {
                        aspectCheckboxes.forEach(checkbox => {
                            checkbox.disabled = false;
                        });
                    }
                }

                // Jalankan fungsi saat halaman pertama kali dimuat
                updateAspectCheckboxes();

                // Event listener untuk perubahan pada checkbox "Pilih Semua Aspek"
                allAspectCheckbox.addEventListener('change', updateAspectCheckboxes);
            });

            let answer_increment = 0;

            function enabledEvent(element, target) {
                if (element.checked) {
                    $('#'.concat(target)).attr('disabled', false);
                    $('#'.concat(target)).attr('required', true);
                } else {
                    $('#'.concat(target)).attr('required', false);
                    $('#'.concat(target)).attr('disabled', true);
                }
            }

            function appendForm(question, arr_of_answer) {
                if (arr_of_answer[0]) {
                    if ($('#question_answer_'.concat(arr_of_answer[2])).val() !== undefined) {
                        answer_increment = parseInt($('#question_answer_'.concat(arr_of_answer[2])).val()) + 1;
                        $.ajax({
                            url: "{{ route('master.question.append') }}",
                            type: "GET",
                            data: {
                                question: 0,
                                answer: arr_of_answer[0],
                                parent: arr_of_answer[2],
                                increment: answer_increment,
                            },
                            success: function(data) {
                                $('#question_answer_'.concat(arr_of_answer[2])).val(answer_increment);
                                $('#'.concat(arr_of_answer[1])).append(data);
                            },
                            error: function(xhr, status, error) {
                                swalError(error);
                            }
                        });
                    } else {
                        answer_increment++;
                        $.ajax({
                            url: "{{ route('master.question.append') }}",
                            type: "GET",
                            data: {
                                question: 0,
                                answer: arr_of_answer[0],
                                parent: arr_of_answer[2],
                                increment: answer_increment,
                            },
                            success: function(data) {
                                $('#'.concat(arr_of_answer[1])).append(data);
                            },
                            error: function(xhr, status, error) {
                                swalError(error);
                            }
                        });
                    }
                }
            }


            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection
