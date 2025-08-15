@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Tambah Pertanyaan</h3>
                            </div>
                            <form action="{{ route('master.question.store') }}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="direction_question" class="col-md-4 control-label text-left">
                                            Petunjuk Pertanyaan
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('direction_question') is-invalid @enderror"
                                                type="text" name="direction_question"
                                                value="{{ old('direction_question') }}" required>
                                            @error('direction_question')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="question" class="col-md-4 control-label text-left">Pertanyaan</label>
                                        <div class="col-md-8 col-sm-12">
                                            <textarea name="question" class="form-control question summernote">{{ old('question') }}</textarea>
                                            @error('question')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="attachment" class="col-md-4 control-label text-left">Gambar </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input type="file" class="form-control" name="attachment" id="documentInput"
                                                accept="image/jpeg,image/jpg,image/png">
                                            <p class="text-danger py-1">* .jpg .jpeg .png</p>
                                        </div>
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
                                                        value="{{ old('time_duration') }}">
                                                    @error('time_duration')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="level" class="col-md-4 control-label text-left">
                                            Level
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="form-check">
                                                <input class="form-check-input" name="all_level" id="all_level"
                                                    type="checkbox">
                                                <label class="form-check-label">Pilih Semua Level</label>
                                            </div>
                                            <div class="form-group ml-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="level[]" id="level1"
                                                        value="1" type="checkbox">
                                                    <label class="form-check-label">Level 1</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="level[]" id="level2"
                                                        value="2" type="checkbox">
                                                    <label class="form-check-label">Level 2</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="level[]" id="level3"
                                                        value="3" type="checkbox">
                                                    <label class="form-check-label">Level 3</label>
                                                </div>
                                            </div>
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
                                            <div class="form-check">
                                                <input class="form-check-input" name="all_aspect" id="all_aspect"
                                                    type="checkbox">
                                                <label class="form-check-label">Pilih Semua Aspek</label>
                                            </div>
                                            <div class="form-group ml-4">
                                                @foreach ($aspects as $aspect)
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="aspect[]"
                                                            id="aspect{{ $aspect->id }}" value="{{ $aspect->id }}"
                                                            type="checkbox">
                                                        <label class="form-check-label">{{ $aspect->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
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
                                            <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
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
                                                @if (old('is_generate_random_answer')) checked @endif>
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
                                                    @if (old('is_random_answer')) checked @endif>
                                                <label class="custom-control-label" for="is_random_answer">Acak
                                                    Semua Jawaban</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Daftar Jawaban
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div id="question_list"></div>
                                            <div class="form-group border rounded p-5">
                                                <div class="text-center p-2" id="add_question" onclick="appendForm()">
                                                    <h4 class="my-auto">
                                                        <i class="fas fa-plus mr-2"></i>
                                                        Tambah Jawaban
                                                    </h4>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Kembali</a>
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('javascript-bottom')
        <script>
            function enabledEvent(element, target) {
                if (element.checked) {
                    $('#'.concat(target)).attr('disabled', false);
                    $('#'.concat(target)).attr('required', true);
                } else {
                    $('#'.concat(target)).attr('required', false);
                    $('#'.concat(target)).attr('disabled', true);
                }
            }

            let answer_increment = 0;

            function appendForm(parentId) { // Gunakan parentId sebagai ID pertanyaan
                answer_increment++;
                $.ajax({
                    url: "{{ route('master.question.append') }}",
                    type: "GET",
                    data: {
                        question: 0,
                        answer: 1,
                        increment: answer_increment,
                        parent: parentId // Gunakan parent ID yang sesuai
                    },
                    success: function(data) {
                        $('#question_list').append(data);
                    },
                    error: function(xhr, status, error) {
                        swalError(error);
                    }
                });
            }


            function remove(target) {
                $('#'.concat(target)).remove();
            }

            const allLevelCheckbox = document.getElementById('all_level');
            const levelCheckboxes = document.querySelectorAll('[id^="level"]');

            allLevelCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    levelCheckboxes.forEach(checkbox => {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                    });
                } else {
                    levelCheckboxes.forEach(checkbox => {
                        checkbox.disabled = false;
                    });
                }
            });

            const allAspectCheckbox = document.getElementById('all_aspect');
            const aspectCheckboxes = document.querySelectorAll('[id^="aspect"]');

            allAspectCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    aspectCheckboxes.forEach(checkbox => {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                    });
                } else {
                    aspectCheckboxes.forEach(checkbox => {
                        checkbox.disabled = false;
                    });
                }
            });

            // Fungsi untuk memvalidasi form sebelum disubmit
            function validateForm(event) {
                let levelChecked = false;
                let aspectChecked = false;

                // Periksa jika "Pilih Semua Level" dicentang
                const allLevelChecked = document.getElementById('all_level').checked;

                // Periksa jika "Pilih Semua Aspek" dicentang
                const allAspectChecked = document.getElementById('all_aspect').checked;

                // Jika "Pilih Semua Level" tidak dicentang, periksa checkbox level
                if (!allLevelChecked) {
                    document.querySelectorAll('[name="level[]"]').forEach(function(checkbox) {
                        if (checkbox.checked) {
                            levelChecked = true;
                        }
                    });
                } else {
                    levelChecked = true; // Jika "Pilih Semua Level" dicentang, dianggap valid
                }

                // Jika "Pilih Semua Aspek" tidak dicentang, periksa checkbox aspect
                if (!allAspectChecked) {
                    document.querySelectorAll('[name="aspect[]"]').forEach(function(checkbox) {
                        if (checkbox.checked) {
                            aspectChecked = true;
                        }
                    });
                } else {
                    aspectChecked = true; // Jika "Pilih Semua Aspek" dicentang, dianggap valid
                }

                // Jika tidak ada level atau aspect yang dicentang, tampilkan alert
                if (!levelChecked) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Peringatan',
                        text: 'Pilih minimal satu level!',
                    });
                    event.preventDefault(); // Cegah form disubmit
                    return false;
                }

                if (!aspectChecked) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Peringatan',
                        text: 'Pilih minimal satu aspek!',
                    });
                    event.preventDefault(); // Cegah form disubmit
                    return false;
                }
            }

            // Tambahkan event listener untuk validasi sebelum form disubmit
            document.querySelector('form').addEventListener('submit', validateForm);

            // Validasi jumlah jawaban benar (min & max 1)
            document.querySelector('form').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('input[type="checkbox"][name*="[is_answer]"]:checked').length;
                if (checked !== 1) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Peringatan',
                        text: 'Harus memilih tepat satu jawaban benar!',
                    });
                }
            });
        </script>
    @endpush
@endsection
