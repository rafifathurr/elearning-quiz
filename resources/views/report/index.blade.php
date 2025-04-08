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
                                            <input type="date" class="form-control" name="start_date" id="start_date"
                                                required>
                                        </div>
                                        <div class="col-md-4 my-1">
                                            <label for="end_date">Tanggal Akhir:</label>
                                            <input type="date" class="form-control" name="end_date" id="end_date"
                                                required>
                                        </div>
                                        <div class="col-md-2 my-1 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100"><i
                                                    class="fa fa-file-excel mr-2"></i>Export Excel</button>
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
