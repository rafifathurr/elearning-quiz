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
                                <h3 class="font-weight-bold">My Class</h3>
                            </div>
                            <div class="card-body">
                                @hasrole('class-operator')
                                    <a href="{{ route('class.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Tambah Kelas
                                    </a>
                                @endhasrole
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable text-center"
                                        id="dt-classadmin">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Nama Kelas</th>
                                                <th>Pertemuan Ke</th>
                                                <th>Total Pertemuan</th>
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
            dataTableAdmin();
        </script>
    @endpush
@endsection
