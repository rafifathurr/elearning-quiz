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
                            <a href="{{ route('order.listOrder') }}" class="btn btn-success btn-sm mr-2"><i
                                    class="fas fa-arrow-left mr-1"></i>Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        @include('js.order.script')
    @endpush
@endsection
