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
                                <h3 class="font-weight-bold">My Test</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table  table-hover w-100 datatable text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Quiz</th>
                                                <th>Durasi Waktu</th>
                                                <th>Jumlah Pertanyaan</th>
                                                <th>Total Skor</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Quiz 1</td>
                                                <td>90 Menit</td>
                                                <td>75 Pertanyaan</td>
                                                <td><span class="text-success">80</span></td>
                                                <td><span class="btn btn-sm btn-danger disabled">Tutup</span></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Quiz 2</td>
                                                <td>120 Menit</td>
                                                <td>60 Pertanyaan</td>
                                                <td><span class="text-danger">40</span></td>
                                                <td><span class="btn btn-sm btn-danger disabled">Tutup</span></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Quiz 3</td>
                                                <td>90 Menit</td>
                                                <td>75 Pertanyaan</td>
                                                <td>N/A</td>
                                                <td><span class="btn btn-sm btn-success">Mulai</span></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Quiz 4</td>
                                                <td>60 Menit</td>
                                                <td>60 Pertanyaan</td>
                                                <td>N/A</td>
                                                <td><span class="btn btn-sm btn-success">Mulai</span></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Quiz 5</td>
                                                <td>180 Menit</td>
                                                <td>90 Pertanyaan</td>
                                                <td>N/A</td>
                                                <td><span class="btn btn-sm btn-success">Mulai</span></td>
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
