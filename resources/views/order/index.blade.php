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
                            <div class="callout callout-info">
                                <h5><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                                <p>Silakan lakukan pembayaran ke rekening berikut:</p>

                                <h6 class="m-2 d-flex align-items-center">
                                    <img src="{{ asset('img/brilogo.png') }}" alt="BRI Logo" class="img-fluid mr-2"
                                        style="height: 25px;">
                                    <span class="fw-bold badge text-white mr-2" style="background-color: #0A3D91;">Bank
                                        BRI</span>
                                    <strong id="rekening" class="mr-2">038501001542300</strong>
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyRekening()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </h6>
                                <small class="text-success" id="copy-alert" style="display: none;">Nomor rekening berhasil
                                    disalin!</small>
                            </div>
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
        <script>
            function copyRekening() {
                var rekening = document.getElementById("rekening").innerText;
                navigator.clipboard.writeText(rekening).then(() => {
                    var alertBox = document.getElementById("copy-alert");
                    alertBox.style.display = "inline"; // Tampilkan alert
                    setTimeout(() => alertBox.style.display = "none", 2000); // Hilangkan alert setelah 2 detik
                });
            }
        </script>
    @endpush
@endsection
