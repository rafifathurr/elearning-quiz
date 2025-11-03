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
                                <h3 class="font-weight-bold">Voucher Paket</h3>
                            </div>
                            <div class="card-body">
                                @hasanyrole('admin|package-manager')
                                    <a href="{{ route('master.voucher.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Tambah Voucher Paket
                                    </a>
                                @endhasrole
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-voucher">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Nama Voucher</th>
                                                <th>Diskon</th>
                                                <th>Fixed Price</th>
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
        @include('js.master.voucher.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
