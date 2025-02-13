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

                                <div class="d-flex flex-wrap align-items-center p-2 bg-white rounded shadow-sm">
                                    <img src="{{ asset('img/brilogo.png') }}" alt="BRI Logo" class="img-fluid mr-2"
                                        style="height: 30px;">
                                    <span class="fw-bold badge text-white px-3 py-2 mr-2"
                                        style="background-color: #0A3D91;">Bank BRI</span>
                                    <div class="d-flex flex-column">
                                        <strong id="rekening" class="text-dark">038501001542300</strong>
                                        <small class="text-muted">a.n. ATLAS KAPITAL PERKASA</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary ml-auto" onclick="copyRekening()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
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
