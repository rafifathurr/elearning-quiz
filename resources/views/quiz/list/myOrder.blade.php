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
                                                <td><span class="text-danger">Pembayaran Batal</span></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Paket Pembayaran 2</td>
                                                <td>Rp. 800.000</td>
                                                <td>12 Kali</td>
                                                <td><button data-toggle="modal" data-target="#assignTo"
                                                        class="btn btn-sm btn-primary">Bayar Sekarang</button></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><span class="text-success">Sudah Dibayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><span class="text-success">Sudah Dibayar</span></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Paket Pembayaran 3</td>
                                                <td>Rp. 400.000</td>
                                                <td>4 Kali</td>
                                                <td><button data-toggle="modal" data-target="#assignTo"
                                                        class="btn btn-sm btn-primary">Bayar Sekarang</button></td>
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
    {{-- Assign To --}}
    <div class="modal fade" id="assignTo">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" id="assign-form" action="#" class="forms-control" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Upload Bukti Pembayaran</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="lampiran">Bukti Pembayaran<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="lampiran[]"
                                accept="image/jpeg,image/jpg,image/png" multiple="true" required>
                            <p class="text-danger py-1">* .png .jpg .jpeg</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary mx-2">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
