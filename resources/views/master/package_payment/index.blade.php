@extends('layouts.section')
@section('content')
    <style>
        .custom-tooltip {
            position: relative;
            cursor: pointer;
        }

        /* Tooltip Style */
        .custom-tooltip:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 125%;
            /* Adjust Position */
            left: 50%;
            transform: translateX(-50%);
            background: maroon;
            /* Background Biru */
            color: #fff;
            /* Warna Teks Putih */
            padding: 5px 10px;
            /* Padding */
            border-radius: 4px;
            /* Rounded Corners */
            white-space: nowrap;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s;
            font-size: 0.85rem;
            /* Ukuran Font */
        }

        /* Tampilkan Tooltip */
        .custom-tooltip:hover::after {
            opacity: 1;
            visibility: visible;
        }
    </style>
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
                                @hasanyrole('admin|package-manager')
                                    <div class="d-flex">
                                        <a href="{{ route('master.package.create') }}" class="btn btn-primary btn-sm m-2">
                                            <i class="fas fa-plus mr-1"></i>
                                            Tambah Paket Test
                                        </a>
                                        <button onclick="exportPackage()" class="btn btn-success btn-sm m-2">
                                            <i class="fas fa-file-export mr-1"></i>
                                            Export Informasi Paket
                                        </button>
                                    </div>
                                @endhasanyrole
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
