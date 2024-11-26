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
                                <h3 class="font-weight-bold">My Order</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table  table-hover w-100 datatable text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Harga</th>
                                                <th>Jumlah Akses</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Paket Pembayaran 1</td>
                                                <td>Rp. 700.000</td>
                                                <td>10 Kali</td>
                                                <td><span class="btn-sm btn-danger">Pembayaran Batal</span></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Paket Pembayaran 2</td>
                                                <td>Rp. 800.000</td>
                                                <td>12 Kali</td>
                                                <td><span class="btn-sm btn-warning">Belum Dibayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><span class="btn-sm btn-success">Sudah Dibayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><span class="btn-sm btn-success">Sudah Dibayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><span class="btn-sm btn-warning">Belum Dibayar</span></td>
                                            </tr>
                                        </tbody>
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
@endsection
