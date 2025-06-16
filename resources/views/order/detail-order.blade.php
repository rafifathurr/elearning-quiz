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
                            @if ($order->status == 2 && !is_null($order->proof_payment))
                                <div class="alert alert-default-danger" role="alert">
                                    <h5 class="font-weight-bold"><i class="fas fa-times-circle text-danger mr-2"></i>Pesanan
                                        Ditolak !
                                    </h5>
                                    <hr>
                                    <div class="row mt-1">
                                        <div class="col-md-2 font-weight-bolder">Alasan Penolakan</div>
                                        <div class="col-md-8">
                                            <span class="d-none d-md-inline">:</span>
                                            <span
                                                class="font-weight-bold">{{ $order->reason ? $order->reason : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <h6 class="col-md-2 font-weight-bold">Bukti Pembayaran</h6>
                                        <h6 class="col-md-8 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            @if (!is_null($order->proof_payment))
                                                <a href="{{ route('order.downloadPayment', $order->id) }}" target="_blank"
                                                    class="text-dark text-decoration-none"><i
                                                        class="fas fa-download mr-1"></i> Lihat Bukti</a>
                                            @else
                                                -
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            @endif
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
                            @if ($order->orderBy)
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

                            @if ($order->status == 10)
                                <h5 class="font-weight-bold">Detail Pesanan</h5>
                                <div>
                                    <div class="row">
                                        <h6 class="col-md-3 font-weight-bold">Waktu Pemesanan</h6>
                                        <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y H:i') }}
                                        </h6>
                                    </div>
                                    <div class="row">
                                        <h6 class="col-md-3 font-weight-bold">Waktu Kirim Bukti Pembayaran</h6>
                                        <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                            {{ \Carbon\Carbon::parse($order->payment_date)->translatedFormat('d F Y H:i') }}
                                        </h6>
                                    </div>
                                    @if ($order->payment_method == 'transfer')
                                        <div class="row">
                                            <h6 class="col-md-3 font-weight-bold">Bukti Pembayaran</h6>
                                            <h6 class="col-md-3 font-weight-bold"> <span class="d-none d-md-inline">:</span>
                                                @if (!is_null($order->proof_payment))
                                                    <a href="{{ route('order.downloadPayment', $order->id) }}"
                                                        target="_blank"><i class="fas fa-download mr-1"></i> Lihat Bukti</a>
                                                @else
                                                    -
                                                @endif
                                            </h6>
                                        </div>
                                    @endif
                                </div>
                            @endif


                            {{-- Detail Pesanan Paket --}}
                            @if ($order_package->isNotEmpty())
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
                            @endif

                            {{-- Detail Pesanan Voucher --}}
                            @if ($order_voucher->isNotEmpty())
                                <div class="table-responsive py-1">
                                    <table id="table-detail" class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Voucher</th>
                                                <th>Tipe Voucher</th>
                                                <th>Harga Voucher</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order_voucher as $item)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $item->voucher->name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->voucher->type_voucher }}
                                                    </td>
                                                    <td>
                                                        {{ 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-primary">
                                            <tr>
                                                <td colspan="3" class="text-right">Total:</td>
                                                <td>{{ 'Rp. ' . number_format($order->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('order.listOrder') }}" class="btn btn-primary btn-sm m-1">
                                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                                </a>
                                @if ($order->status == 10)
                                    @hasanyrole('admin|finance')
                                        <div>
                                            <button class="btn btn-sm btn-success m-1"
                                                onclick="approveOrder({{ $order->id }})">
                                                <i class="fas fa-check mr-1"></i>Terima
                                            </button>
                                            <button class="btn btn-sm btn-danger m-1"
                                                onclick="rejectOrder({{ $order->id }})">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </div>
                                    @endhasanyrole
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($order->status == 100)
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
                                            <h6 class="col-md-8"> <span class="d-none d-md-inline">:</span>
                                                @if (!is_null($order->proof_payment))
                                                    <a href="{{ route('order.downloadPayment', $order->id) }}"
                                                        target="_blank"
                                                        class="text-primary text-decoration-none font-weight-bold"><i
                                                            class="fas fa-download mr-1"></i> Lihat
                                                        Bukti</a>
                                                @else
                                                    -
                                                @endif
                                            </h6>
                                        </div>
                                    @else
                                        @if ($order->supportBriva)
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
    @endpush
@endsection
