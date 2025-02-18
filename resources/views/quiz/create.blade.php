@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">

        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Tambah Test</h3>
                            </div>

                            <form action="{{ route('admin.quiz.store') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header bg-gray-light">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Konfigurasi Test
                                            </h2>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Judul
                                                    Test
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input class="form-control @error('name') is-invalid @enderror"
                                                        type="text" name="name" id="name"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Tipe Aspek
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select name="type_aspect" id="type_aspect" class="form-control"
                                                        required>
                                                        <option value="">Pilih Tipe Aspek</option>
                                                        <option value="kecerdasan">Kecerdasan</option>
                                                        <option value="kepribadian">Kepribadian</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Konfigurasi Waktu
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 control-label text-left">Durasi
                                                    Waktu (Detik)
                                                    <span class="text-danger ml-1">*</span>
                                                </label>
                                                <div class="col-lg-3 col-md-4 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input
                                                                class="form-control @error('time_duration') is-invalid @enderror"
                                                                type="number"
                                                                name="time_duration"value="{{ old('time_duration') }}"
                                                                required>
                                                            @error('time_duration')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-gray-light" id="headingTwo">
                                            <h2 class="card-title mb-0 font-weight-bold">
                                                Daftar Aspek
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div id="aspect_list"></div>
                                            <div class="form-group border rounded p-5">
                                                <div class="text-center p-2" id="add_question" onclick="appendForm(true)">
                                                    <h4 class="my-auto">
                                                        <i class="fas fa-plus mr-2"></i>
                                                        Tambah Aspek
                                                    </h4>
                                                </div>
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
                            </form>
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
            let aspect_increment = 1;

            function appendForm(aspect_quiz) {
                aspect_increment++; // Increment form
                const type_aspect = $('#type_aspect').val(); // Ambil tipe aspek yang dipilih

                $.ajax({
                    url: "{{ route('admin.quiz.append') }}",
                    type: "GET",
                    data: {
                        aspect_quiz: aspect_quiz, // Kirim status aspect_quiz
                        increment: aspect_increment, // Kirim increment
                        type_aspect: type_aspect, // Kirim tipe_aspect yang dipilih
                    },
                    success: function(data) {
                        $('#aspect_list').append(data); // Tambahkan form ke dalam container
                    },
                    error: function(xhr, status, error) {
                        swalError(error); // Tampilkan pesan error
                    }
                });
            }
            let previous_type_aspect = $('#type_aspect').val(); // Simpan tipe_aspect yang pertama kali dipilih
            let hasChosenAspect = false; // Flag untuk memeriksa apakah pengguna sudah memilih aspek

            // Event listener untuk perubahan tipe_aspect
            $('#type_aspect').on('change', function() {
                const current_type_aspect = $(this).val(); // Ambil tipe_aspect yang baru
                console.log('Tipe aspek berubah:', current_type_aspect); // Debugging

                // Periksa apakah tipe_aspect berubah
                if (current_type_aspect !== previous_type_aspect) {
                    console.log('Tipe aspek berbeda, memeriksa apakah sudah memilih aspek...');

                    // Tampilkan swalFire hanya jika pengguna sudah memilih aspek sebelumnya
                    if (hasChosenAspect) {
                        Swal.fire({
                            title: 'Apakah Anda Yakin Mengubah Tipe Aspek? Data Aspek Anda Akan Terhapus!',
                            icon: 'question',
                            showCancelButton: true,
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary mr-2 mb-3',
                                cancelButton: 'btn btn-danger mb-3',
                            },
                            buttonsStyling: false,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#aspect_list').html(''); // Menghapus daftar aspek
                                updateAspectList(current_type_aspect);
                            } else {
                                $('#type_aspect').val(previous_type_aspect).trigger('change');
                            }
                        });
                    } else {
                        updateAspectList(current_type_aspect);
                        previous_type_aspect = current_type_aspect;
                    }
                }


            });

            // Event listener untuk form quiz_aspect, periksa apakah pengguna sudah memilih aspek
            $(document).on('change', '[id^="quiz_aspect"]', function() {
                // Tandai bahwa pengguna sudah memilih aspek
                hasChosenAspect = true;
            });

            // Fungsi untuk memperbarui daftar aspek
            function updateAspectList(type_aspect) {
                $.ajax({
                    url: "{{ route('admin.quiz.append') }}",
                    type: "GET",
                    data: {
                        type_aspect: type_aspect,
                        increment: aspect_increment,
                    },
                    success: function(data) {
                        $('#aspect_list').html(data);
                    },
                    error: function(xhr, status, error) {
                        swalError(error);
                    }
                });
            }

            function remove(target) {
                $('#'.concat(target)).remove();
            }
        </script>
    @endpush
@endsection
