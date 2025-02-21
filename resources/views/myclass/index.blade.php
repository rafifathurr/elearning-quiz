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
                        @if ($myClass && $myClass->isNotEmpty())
                            <div class="alert alert-default-info" role="alert">
                                <h4 class="alert-heading"> Kelas yang belum dimulai</h4>
                                <hr>
                                @foreach ($myClass as $class)
                                    <p class="mb-1">{{ $loop->iteration . '. ' . $class->package->name }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">My Class</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-myclass">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Nama Kelas</th>
                                                <th>Jumlah Pertemuan</th>
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
            dataTable();
        </script>
    @endpush
@endsection
