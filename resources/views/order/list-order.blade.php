@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card  card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    Daftar Order
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
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
        <script>
            dataTableAdmin();
        </script>
    @endpush
@endsection
