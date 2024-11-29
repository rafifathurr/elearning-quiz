@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    @role('user')
                                        My Order
                                    @else
                                        Daftar Order
                                    @endrole
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-order">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Harga</th>
                                                <th>Jumlah Pertemuan</th>
                                                <th>Metode Pembayaran</th>
                                                @hasrole('user')
                                                    <th>Status</th>
                                                @else
                                                    <th>Bukti Pembayaran</th>
                                                @endhasrole
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    @push('javascript-bottom')
        @include('js.order.script')
        @role('admin')
            <script>
                dataTableAdmin();
            </script>
        @else
            <script>
                dataTable();
            </script>
        @endrole
    @endpush
@endsection
