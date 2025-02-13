@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card  card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    Riwayat Order
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url" value="{{ url()->current() }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-history">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Order Id</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Status Pembayaran</th>
                                                <th>Daftar Paket</th>
                                                <th>Total Pembayaran</th>
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
        @include('js.order.script')
        <script>
            history();
        </script>
    @endpush
@endsection
