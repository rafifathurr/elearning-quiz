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
                                @hasrole('counselor')
                                    @if (!is_null($orderFor))
                                        <h3 class="font-weight-bold">
                                            Order Untuk {{ $orderFor->user->name }}
                                        </h3>
                                    @else
                                        <h3 class="font-weight-bold">
                                            My Order
                                        </h3>
                                    @endif
                                @else
                                    <h3 class="font-weight-bold">
                                        My Order
                                    </h3>
                                @endhasrole

                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
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
            dataTable();
        </script>
    @endpush
@endsection
