@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    Laporan Pendapatan
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('laporan.export') }}" method="GET">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-md-4 my-1">
                                            <label for="start_date">Tanggal Awal:</label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                name="start_date" id="start_date" value="{{ old('start_date') }}"
                                                max="{{ date('Y-m-d') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 my-1">
                                            <label for="end_date">Tanggal Akhir:</label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror" name="end_date"
                                                id="end_date" value="{{ old('end_date') }}" max="{{ date('Y-m-d') }}"
                                                required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2 my-1 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fa fa-file-excel mr-2"></i>Export Excel
                                            </button>
                                        </div>
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
    @endpush
@endsection
