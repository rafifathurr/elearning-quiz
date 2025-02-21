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
                        <a href="{{ route('myclass.index') }}" class="btn btn-primary btn-sm my-2"><i
                                class="fas fa-arrow-left"></i> Kembali</a>
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Daftar Test Kelas | {{ $className->classPackage->name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-myclass">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Test</th>
                                                <th>Tipe Test</th>
                                                <th>Waktu Test Dibuka</th>
                                                <th>Waktu Test Ditutup</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
        @include('js.myclass.script')
        <script>
            dataTableDetail();
        </script>
    @endpush
@endsection
