@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Tambah Tes Kecermatan</h3>
                            </div>

                            <form method="post" action="{{ route('master.kecermatan.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Judul Test
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input type="text" name="name" id="name" placeholder="Judul Test"
                                                class="form-control" required>
                                            @error('name')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="time_duration" class="col-md-4 control-label text-left">Durasi Waktu
                                            (Detik)
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('time_duration') is-invalid @enderror"
                                                type="number" name="time_duration" value="{{ old('time_duration') }}"
                                                required placeholder="Durasi Waktu">
                                            @error('time_duration')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="questions-container">
                                        <div class="card mb-3 question-item">
                                            <div class="card-body">
                                                <input type="hidden" name="nama_kombinasi[]" value="kombinasi_1">
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
                                                        @error('type_random_question.*')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-4 control-label text-left">Jumlah Pertanyaan
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="number" name="qty[]" placeholder="Jumlah Pertanyaan"
                                                            class="form-control" required>
                                                        @error('qty.*')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="durasi_kombinasi"
                                                        class="col-md-4 control-label text-left">Durasi Waktu (Detik)
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input
                                                            class="form-control @error('durasi_kombinasi.*') is-invalid @enderror"
                                                            type="number" name="durasi_kombinasi[]" required
                                                            placeholder="Durasi Waktu">
                                                        @error('durasi_kombinasi.*')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    </div>

    @push('javascript-bottom')
        @include('js.master.kecermatan.script')
    @endpush
@endsection
