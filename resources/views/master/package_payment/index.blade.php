@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Paket Test</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <a href="{{ route('master.package.create') }}" class="btn btn-primary btn-sm m-2">
                                        <i class="fas fa-plus mr-1"></i>
                                        Tambah Paket Test
                                    </a>
                                    <a href="{{ route('master.member.index') }}" class="btn btn-secondary btn-sm m-2">
                                        <i class="fas fa-user-check mr-1"></i>
                                        Daftar Peserta
                                    </a>
                                </div>
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-package">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Tipe Paket</th>
                                                <th>Pertemuan</th>
                                                <th>Maksimal Peserta</th>
                                                <th>Jadwal Kelas</th>
                                                <th>Harga</th>
                                                <th>Daftar Test</th>
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
        @include('js.master.package_payment.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
