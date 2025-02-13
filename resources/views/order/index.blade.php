@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        @hasrole('user')
                        @endhasrole
                        <div class="card  card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    @hasrole('user')
                                        My Order
                                    @else
                                        Daftar Order
                                    @endhasrole
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    @hasrole('user')
                                        <table class="table table-bordered table-hover w-100 datatable" id="dt-order">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Paket</th>
                                                    <th>Kelas</th>
                                                    <th>Jadwal Kelas</th>
                                                    <th>Harga Paket</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="bg-gray-light">
                                                <tr>
                                                    <th colspan="4" class="text-right">Total:</th>
                                                    <th class="text-left"id="totalPrice"></th>
                                                    <th class="text-center">
                                                        <button class="btn btn-sm btn-success" id="payButton"
                                                            style="display:none">Bayar Sekarang
                                                        </button>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @else
                                        <table class="table table-bordered table-hover w-100 datatable" id="dt-order">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pengguna</th>
                                                    <th>Total Harga</th>
                                                    <th>Metode Pembayaran</th>
                                                    <th>Waktu Pembayaran</th>
                                                    <th>Bukti Pembayaran</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    @endhasrole
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
