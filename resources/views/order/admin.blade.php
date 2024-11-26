@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Daftar Order</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table  table-hover w-100 datatable text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengguna</th>
                                                <th>Paket Pembayaran</th>
                                                <th>Harga</th>
                                                <th>Bukti Pembayaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Ilham J</td>
                                                <td>Paket Pembayaran 1</td>
                                                <td>Rp. 700.000</td>
                                                <td> <label class="m-2">
                                                        <a href="{{ asset('dist/adminlte/img/kelas2.jpg') }}"
                                                            target="_blank">
                                                            <i class="fas fa-download mr-1"></i>
                                                            Bukti Pembayaran
                                                        </a>
                                                    </label></td>
                                                <td><span class="text-danger">Ditolak</span></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Bambang S</td>
                                                <td>Paket Pembayaran 2</td>
                                                <td>Rp. 800.000</td>
                                                <td> <label class="m-2">
                                                        <a href="{{ asset('dist/adminlte/img/bukti.png') }}"
                                                            target="_blank">
                                                            <i class="fas fa-download mr-1"></i>
                                                            Bukti Pembayaran
                                                        </a>
                                                    </label></td>
                                                <td><span class="text-success">Diterima</span></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Cinta Y</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>-</td>
                                                <td><span class="text-warning">Belum Bayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Agus T</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>-</td>
                                                <td><span class="text-warning">Belum Bayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Budi S</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>-</td>
                                                <td><span class="text-warning">Belum Bayar</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
