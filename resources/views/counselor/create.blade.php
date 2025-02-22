@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Buka Kelas</h3>
                            </div>

                            <!-- form start -->
                            <form method="post" action="{{ route('class.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Nama Kelas
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                                name="name" id="name" value="{{ old('name') }}"
                                                placeholder="Nama Kelas" required>
                                            @error('name')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Select Paket -->
                                    <div class="form-group row">
                                        <label class="col-md-4 control-label text-left" for="package_id">Paket <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8 col-sm-12">
                                            <select class="form-control @error('package_id') is-invalid @enderror"
                                                id="package_id" name="package_id" required>
                                                <option value="" selected>Pilih Paket</option>
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('package_id')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Select Jadwal -->
                                    <div class="form-group row">
                                        <label class="col-md-4 control-label text-left" for="date_class_id">Jadwal Kelas
                                            <span class="text-danger">*</span></label>
                                        <div class="col-md-8 col-sm-12">
                                            <select class="form-control @error('date_class_id') is-invalid @enderror"
                                                id="date_class_id" name="date_class_id" data-placeholder="Pilih Jadwal"
                                                required>
                                            </select>
                                            @error('date_class_id')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="card card-info">
                                        <div class="card-header font-weight-bold">
                                            Daftar Konselor
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="counselor_id" class="col-md-4 control-label text-left">Pilih
                                                    Konselor<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select
                                                        class="form-control @error('counselor_id[]') is-invalid @enderror"
                                                        name="counselor_id[]" id="counselor_id"
                                                        data-placeholder="Pilih Konselor" style="width: 100%;" required>
                                                        @foreach ($counselors as $counselor)
                                                            <option value="{{ $counselor->id }}">
                                                                {{ $counselor->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('counselor_id[]')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-info">
                                        <div class="card-header font-weight-bold">
                                            Dafar Peserta
                                        </div>
                                        <div class="card-body">
                                            <!-- Select Peserta (Order Packages) -->
                                            <div class="form-group row">
                                                <label for="order_package_id" class="col-md-4 control-label text-left">Pilih
                                                    Peserta<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <select
                                                        class="form-control @error('order_package_id[]') is-invalid @enderror"
                                                        name="order_package_id[]" id="order_package_id"
                                                        data-placeholder="Pilih Peserta" style="width: 100%;" required>
                                                        <option value="">Pilih Peserta Terlebih Dahulu</option>
                                                    </select>
                                                    @error('order_package_id[]')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3 ">
                                        <div class="d-flex justify-content-end mt-3">

                                            <a href="{{ route('class.index') }}" class="btn btn-danger mr-2">
                                                Kembali</a>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('javascript-bottom')
        <script>
            $('#package_id').select2();
            $('#date_class_id').select2();
            $('#counselor_id').select2({
                multiple: true,
            });
            $('#order_package_id').select2({
                multiple: true,
            });

            $('#counselor_id').val('').trigger('change');
            $('#order_package_id').val('').trigger('change');
        </script>

        <script>
            $(document).ready(function() {
                function getOrderPackages() {
                    var package_id = $('#package_id').val();
                    var date_in_class = $('#date_class_id option:selected').text();
                    $('#order_package_id').empty(); // Kosongkan opsi sebelumnya

                    // Cek jika kedua parameter terisi
                    if (package_id && date_class_id) {
                        $.ajax({
                            url: '{{ url('class/get-order-packages') }}/' + package_id + '/' +
                                encodeURIComponent(date_in_class),
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#order_package_id').append('<option value="">Pilih Peserta</option>');
                                $.each(data, function(key, value) {
                                    $('#order_package_id').append(
                                        '<option value="' + value.id + '">' + value.order.user
                                        .name + '</option>'
                                    );
                                });
                            }
                        });
                    } else {
                        $('#order_package_id').append(
                            '<option value="">Pilih Paket dan Jadwal Terlebih Dahulu</option>');
                    }
                }

                // Event Listener ketika Paket berubah
                $('#package_id').on('change', function() {
                    getOrderPackages();
                });

                // Event Listener ketika Jadwal berubah
                $('#date_class_id').on('change', function() {
                    getOrderPackages();
                });
            });
            $(document).ready(function() {
                // Event Listener ketika Paket berubah
                $('#package_id').on('change', function() {
                    var package_id = $(this).val();
                    $('#date_class_id').empty(); // Kosongkan opsi sebelumnya

                    if (package_id) {
                        $.ajax({
                            url: '{{ url('class/get-date-classes') }}/' + package_id,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#date_class_id').append(
                                    '<option value="">Pilih Jadwal Kelas</option>');
                                $.each(data, function(key, value) {
                                    $('#date_class_id').append(
                                        '<option value="' + value.id + '">' + value
                                        .name + '</option>'
                                    );
                                });
                            }
                        });
                    } else {
                        $('#date_class_id').append('<option value="">Pilih Paket Terlebih Dahulu</option>');
                    }
                });
            });
        </script>

        @include('js.master.user.script')
    @endpush
@endsection
