@extends('layouts.section')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Test Kecermatan</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card ">

                        <!-- form start -->
                        <form method="post" action="{{ route('master.kecermatan.update', [($id = $quiz->id)]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 control-label text-left">Judul Test
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input type="text" name="name" id="name" placeholder="Judul Test"
                                            class="form-control" value="{{ $quiz->name }}" required>
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="time_duration" class="col-md-4 control-label text-left">Durasi Waktu (Detik)
                                        <span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-12">
                                        <input class="form-control @error('time_duration') is-invalid @enderror"
                                            type="number" name="time_duration" value="{{ $quiz->time_duration }}" required
                                            placeholder="Durasi Waktu">
                                        @error('time_duration')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="questions-container">
                                    @foreach ($groupedQuestions as $nama_kombinasi => $questions)
                                        @php
                                            // Ambil data pertama dalam setiap kelompok untuk menentukan type_random_question dan durasi_kombinasi
                                            $firstQuestion = $questions[0];
                                        @endphp
                                        <div class="card mb-3 question-item">
                                            <div class="card-body">
                                                <input type="hidden" name="nama_kombinasi[]" value="{{ $nama_kombinasi }}">
                                                <h4>Pertanyaan</h4>
                                                <div class="form-group row">
                                                    <label class="col-md-4 control-label text-left">Tipe Random Pertanyaan
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <select name="type_random_question[]" class="form-control" required>
                                                            <option value="">Pilih Tipe Pertanyaan</option>
                                                            <option value="angka"
                                                                {{ is_numeric($firstQuestion['correct_answer']) ? 'selected' : '' }}>
                                                                Angka</option>
                                                            <option value="huruf"
                                                                {{ ctype_alpha($firstQuestion['correct_answer']) ? 'selected' : '' }}>
                                                                Huruf</option>
                                                            <option value="simbol"
                                                                {{ !ctype_alpha($firstQuestion['correct_answer']) && !is_numeric($firstQuestion['correct_answer']) ? 'selected' : '' }}>
                                                                Simbol
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-4 control-label text-left">Jumlah Pertanyaan
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="number" name="qty[]" value="{{ count($questions) }}"
                                                            placeholder="Jumlah Pertanyaan" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="durasi_kombinasi"
                                                        class="col-md-4 control-label text-left">Durasi Waktu (Detik)
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input class="form-control" type="number" name="durasi_kombinasi[]"
                                                            value="{{ $firstQuestion['durasi_kombinasi'] }}"
                                                            placeholder="Durasi Waktu" required>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-danger remove-question">Hapus
                                                    Pertanyaan</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>



                                <button type="button" id="add-question" class="btn btn-success mb-3">Tambah
                                    Pertanyaan</button>

                                <div class="pt-3 d-flex">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>

                        <script>
                            let questionCount = 1; // Untuk memberikan nomor pada nama_kombinasi

                            document.getElementById('add-question').addEventListener('click', function() {
                                const container = document.getElementById('questions-container');
                                questionCount++; // Increment nomor kombinasi
                                const questionTemplate = `
                                    <div class="card mb-3 question-item">
                                        <div class="card-body">
                                            <input type="hidden" name="nama_kombinasi[]" value="kombinasi_${questionCount}">
                                            <h4>Pertanyaan</h4>
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label text-left">Tipe Random Pertanyaan
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select name="type_random_question[]" class="form-control" required>
                                                        <option value="">Pilih Tipe Pertanyaan</option>
                                                        <option value="angka">Angka</option>
                                                        <option value="huruf">Huruf</option>
                                                        <option value="simbol">Simbol</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label text-left">Jumlah Pertanyaan
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input type="number" name="qty[]" placeholder="Jumlah Pertanyaan" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="durasi_kombinasi"
                                                    class="col-md-4 control-label text-left">Durasi Waktu (Detik)
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input
                                                        class="form-control"
                                                        type="number" name="durasi_kombinasi[]"
                                                        placeholder="Durasi Waktu" required>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger remove-question">Hapus Pertanyaan</button>
                                        </div>
                                    </div>
                                `;
                                container.insertAdjacentHTML('beforeend', questionTemplate);
                            });

                            document.addEventListener('click', function(event) {
                                if (event.target.classList.contains('remove-question')) {
                                    event.target.closest('.question-item').remove();
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        @include('js.master.kecermatan.script')
    @endpush
@endsection
