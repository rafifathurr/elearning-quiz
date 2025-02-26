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
                            {{-- Detail Pemesan --}}
                            <h5 class="font-weight-bold">Detail Pemesan</h5>
                            <div class="alert bg-lightblue" role="alert">
                                <div class="row">
                                    <h6 class="col-md-3 font-weight-bold">Nama</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->name }}</h6>
                                </div>
                                <div class="row">
                                    <h6 class="col-md-3 font-weight-bold">Email</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->email }}</h6>
                                </div>
                                <div class="row">
                                    <h6 class="col-md-3 font-weight-bold">No Handphone</h6>
                                    <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                        {{ $order->user->phone }}</h6>
                                </div>
                            </div>

                            {{-- Detail Dipesankan Oleh --}}
                            @if ((!is_null($order->order_by) && $order->order_by != Auth::user()->id) || $order->user_id != Auth::user()->id)
                                <h5 class="font-weight-bold">Dipesankan Oleh</h5>
                                <div class="alert bg-maroon" role="alert">
                                    <div class="row">
                                        <h6 class="col-md-3 font-weight-bold">Nama</h6>
                                        <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            {{ $order->orderBy->name }}</h6>
                                    </div>
                                    <div class="row">
                                        <h6 class="col-md-3 font-weight-bold">Email</h6>
                                        <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            {{ $order->orderBy->email }}</h6>
                                    </div>
                                    <div class="row">
                                        <h6 class="col-md-3 font-weight-bold">No Handphone</h6>
                                        <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            {{ $order->orderBy->phone }}</h6>
                                    </div>
                                </div>
                            @endif


                            {{-- Detail Pesanan --}}
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
                                                    {{ 'Rp. ' . number_format($item->price ?? optional($item->package)->price, 0, ',', '.') }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr>
                                            <td colspan="4" class="text-right">Total:</td>
                                            <td>{{ 'Rp. ' . number_format($order->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <a href="{{ route('order.history') }}" class="btn btn-success btn-sm mr-2"><i
                                    class="fas fa-arrow-left mr-1"></i>Kembali</a>
                        </div>
                    </div>
                    @if ($order->status == 2)
                        {{-- Transfer --}}
                        @if ($order->payment_method == 'transfer')
                            <div class="callout callout-info">
                                <h5><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                                <p>Silakan lakukan pembayaran ke rekening berikut:</p>

                                <div class="d-flex flex-wrap align-items-center p-2 bg-white rounded shadow-sm">
                                    <img src="{{ asset('img/brilogo.png') }}" alt="BRI Logo" class="img-fluid mr-2"
                                        style="height: 30px;">
                                    <span class="fw-bold badge text-white px-3 py-2 mr-2"
                                        style="background-color: #0A3D91;">Bank
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
                                        <div class="alert alert-default-info d-flex">
                                            <h6 class="alert-heading"> Berita:
                                                <strong>"
                                                    <strong id="berita">{{ 'BC' . $year . $order->id }}</strong>"
                                                </strong>
                                            </h6>
                                            <button class="btn btn-sm btn-outline-primary mx-2" onclick="copyBerita()">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                            <small class="text-success font-weight-bolder" id="copy-berita-alert"
                                                style="display: none;">Berita
                                                berhasil
                                                disalin!</small>
                                        </div>

                                        <form action="{{ route('order.uploadPayment', ['id' => $order->id]) }}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('patch')
                                            <div class="form-group row ">
                                                <label for="proof_payment" class="col-md-4 control-label text-left">Upload
                                                    Bukti
                                                    Pembayaran <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="proof_payment"
                                                    id="proof_payment" accept="image/jpeg,image/jpg,image/png" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Kirim
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif ($order->payment_method == 'briva')
                            <div class="callout callout-info">
                                <h5><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                                <p>Silakan lakukan pembayaran menggunakan virtual account berikut:</p>

                                <div class="card border-info mt-3 shadow-sm">
                                    <div class="card-body text-center d-flex justify-content-center align-items-center">
                                        <h4 class="font-weight-bold mb-0 text-primary" id="va"
                                            style="margin-right: 10px;">
                                            {{ $order->supportBriva->va }}
                                        </h4>
                                        <button class="btn btn-outline-primary btn-sm" onclick="copyVA()"
                                            title="Salin VA" style="border-radius: 50%;">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div id="copySuccess" class="text-success text-center"
                                        style="display: none; font-size: 0.9rem;">
                                        <i class="fas fa-check-circle"></i> VA berhasil disalin!
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif ($order->status == 100)
                        <div class="callout callout-info">
                            <h5><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                            <div class="card shadow-sm mt-3 border">
                                <div class="card-body">
                                    <h4 class="text-success mb-3">
                                        <i class="fas fa-check-circle"></i> Pembayaran Berhasil
                                    </h4>

                                    @if ($order->payment_method == 'transfer')
                                        <div class="row mt-1">
                                            <div class="col-md-2 text-muted">Metode Pembayaran</div>
                                            <div class="col-md-8">
                                                <span class="d-none d-md-inline">:</span>
                                                <span class="badge bg-lightblue p-2 m-1"
                                                    style="font-size: 0.9rem; font-weight: bold;">Transfer</span>
                                            </div>
                                        </div>
                                        @if (!is_null($order->rekening_number))
                                            <div class="row mt-1">
                                                <div class="col-md-2 text-muted">Rekening Tujuan</div>
                                                <div class="col-md-8">
                                                    <span class="d-none d-md-inline">:</span>
                                                    <span
                                                        class="text-lightblue font-weight-bold">{{ $order->rekening_number }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row mt-2">
                                            <h6 class="col-md-2 text-muted">Bukti Pembayaran</h6>
                                            <h6 class="col-md-8">
                                                @if (!is_null($order->proof_payment))
                                                    <img src="{{ route('order.viewPayment', $order->id) }}"
                                                        alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm mt-2"
                                                        style="max-width: 25%; height: auto;">
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </h6>
                                        </div>
                                    @else
                                        <div class="row mt-1">
                                            <div class="col-md-2 text-muted">Metode Pembayaran</div>
                                            <div class="col-md-8">
                                                <span class="d-none d-md-inline">:</span>
                                                <span class="badge bg-lightblue p-2 m-1"
                                                    style="font-size: 0.9rem; font-weight: bold;">BRIVA</span>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-2 text-muted">Nomor BRIVA</div>
                                            <div class="col-md-8">
                                                <span class="d-none d-md-inline">:</span>
                                                <span
                                                    class="text-info font-weight-bold">{{ $order->supportBriva->va }}</span>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-2 text-muted">Waktu Pembayaran</div>
                                            <div class="col-md-8">
                                                <span class="d-none d-md-inline">:</span>
                                                <span class="font-weight-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($order->supportBriva->payment_time)->translatedFormat('d F Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif


                                    @if ($order->approveBy)
                                        <hr>
                                        <div class="row mt-1">
                                            <h6 class="col-md-2 text-muted">Nama Penerima</h6>
                                            <h6 class="col-md-8 font-weight-bolder"> <span
                                                    class="d-none d-md-inline">:</span>
                                                {{ $order->approveBy->name }}
                                            </h6>
                                        </div>
                                        <div class="row mt-1">
                                            <h6 class="col-md-2 text-muted">Waktu Diterima</h6>
                                            <h6 class="col-md-8 font-weight-bolder"> <span
                                                    class="d-none d-md-inline">:</span>
                                                {{ \Carbon\Carbon::parse($order->approval_date)->translatedFormat('d F Y H:i') }}
                                            </h6>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif

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

            function copyBerita() {
                var berita = document.getElementById("berita").innerText;
                navigator.clipboard.writeText(berita).then(() => {
                    var beritaBox = document.getElementById("copy-berita-alert");
                    beritaBox.style.display = "inline";
                    setTimeout(() => beritaBox.style.display = "none", 2000);
                });
            }

            function copyVA() {
                var va = document.getElementById("va").innerText;
                navigator.clipboard.writeText(va).then(() => {
                    var alert = document.getElementById("copySuccess");
                    alert.style.display = "block";
                    alert.style.opacity = 1;

                    setTimeout(() => {
                        alert.style.transition = "opacity 0.5s";
                        alert.style.opacity = 0;
                    }, 1500);

                    setTimeout(() => {
                        alert.style.display = "none";
                        alert.style.transition = ""; // Reset transition
                    }, 2000);
                });
            }
        </script>
    @endpush
@endsection
