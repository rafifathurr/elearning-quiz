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
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-warning text-dark d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <h5 class="m-0 font-weight-bold">Kelas yang Belum Dimulai</h5>
                                </div>
                                <div class="card-body p-3">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($myClass as $class)
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-book text-warning mr-2"></i>
                                                <span
                                                    class="font-weight-bold">{{ $loop->iteration . '. ' . $class->package->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif


                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Kelas Saya</h3>
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
