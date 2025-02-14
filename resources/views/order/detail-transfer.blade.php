@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <div class="d-flex justify-content-between  align-items-center">
                                <?php
                                $year = \Carbon\Carbon::parse($order->created_at)->format('y'); ?>
                                <h3 class="card-title font-weight-bold">Order Id #{{ 'BC' . $year . $order->id }}
                                </h3>
                                <h3 class="card-title font-weight-bold">
                                    {{ \Carbon\Carbon::parse($order->payment_date)->translatedFormat('d F Y ') }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-bold">Detail Pemesan</h5>
                            <div class="alert alert-default-info" role="alert">
                                <div class="row">
                                    <h6 class="col-md-3">Nama</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->name }}</h6>
                                </div>
                                <div class="row">
                                    <h6 class="col-md-3">Email</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->email }}</h6>
                                </div>
                                <div class="row">
                                    <h6 class="col-md-3">No Handphone</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->phone }}</h6>
                                </div>
                            </div>
                            <h5 class="font-weight-bold">Detail Pesanan</h5>
                            <div class="table-responsive py-1">
                                <table id="table-detail" class="table table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Paket</th>
                                            <th>Kelas</th>
                                            <th>Jadwal Kelas</th>
                                            <th>Harga Paket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order_package as $item)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $item->package->name }}
                                                </td>
                                                <td>
                                                    {{ !is_null($item->class) && $item->class > 0 ? $item->class . 'x Pertemuan' : '-' }}
                                                </td>
                                                <td>
                                                    {{ $item->dateClass ? $item->dateClass->name : '-' }}
                                                </td>
                                                <td>
                                                    {{ 'Rp. ' . number_format($item->package->price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr>
                                            <td colspan="4" class="text-right">Total:</td>
                                            <td>{{ 'Rp. ' . number_format($totalPrice, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <a href="{{ route('order.history') }}" class="btn btn-success btn-sm mr-2"><i
                                    class="fas fa-arrow-left mr-1"></i>Kembali</a>
                        </div>
                    </div>

                    <div class="callout callout-info">
                        <h5><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                        <p>Silakan lakukan pembayaran ke rekening berikut:</p>

                        <div class="d-flex flex-wrap align-items-center p-2 bg-white rounded shadow-sm">
                            <img src="{{ asset('img/brilogo.png') }}" alt="BRI Logo" class="img-fluid mr-2"
                                style="height: 30px;">
                            <span class="fw-bold badge text-white px-3 py-2 mr-2" style="background-color: #0A3D91;">Bank
                                BRI</span>
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

                        <div class="card mt-4 border shadow-sm">
                            <div class="card-body">
                                <div class="alert alert-default-info" role="alert">
                                    <h6 class="alert-heading"> Berita:
                                        <strong>"{{ 'BC' . $year . $order->id . '-' . $order->user->name }}"</strong>
                                    </h6>
                                </div>
                                <form action="{{ route('order.uploadPayment', ['id' => $order->id]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <div class="form-group row ">
                                        <label for="proof_payment" class="col-md-4 control-label text-left">Upload Bukti
                                            Pembayaran <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="proof_payment" id="proof_payment"
                                            accept="image/jpeg,image/jpg,image/png" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Kirim
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        @include('js.order.script')
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
