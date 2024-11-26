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
                                <h3 class="font-weight-bold">Kelas Saya</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive  table-striped projects mt-3">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengguna</th>
                                                <th>Progres</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Ilham J</td>
                                                <td class="project_progress">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 57%">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        57% Complete
                                                    </small>
                                                </td>
                                                <td><span class="text-success">Bagus</span></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Bambang S</td>
                                                <td class="project_progress">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            aria-valuenow="37" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 37%">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        37% Complete
                                                    </small>
                                                </td>
                                                <td><span class="text-success">Bagus</span></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Cinta Y</td>
                                                <td class="project_progress">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 70%">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        57% Complete
                                                    </small>
                                                </td>
                                                <td><span class="text-success">Bagus</span></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>

                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Agus T</td>
                                                <td class="project_progress">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 43%">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        43% Complete
                                                    </small>
                                                </td>
                                                <td><span class="text-success">Bagus</span></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>

                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Budi S</td>
                                                <td class="project_progress">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 33%">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        33% Complete
                                                    </small>
                                                </td>
                                                <td><span class="text-success">Bagus</span></td>
                                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>

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
