@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Daftar Peserta</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('master.member.export') }}" method="GET">
                                    <div class="row mb-3">
                                        <div class="col-md-4 my-1">
                                            <label for="reservation">Rentang Tanggal:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="reservation"
                                                    name="reservation" placeholder="-- Semua tanggal --">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-4 my-1">
                                            <label for="packageFilter">Nama Paket:</label>
                                            <select id="packageFilter" name="packageFilter" class="form-control">
                                                <option value="">-- Semua Paket --</option>
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}">{{ $package->name }} |
                                                        {{ $package->typePackage->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 my-1">
                                            <label for="dateClassFilter">Jadwal Kelas:</label>
                                            <select id="dateClassFilter" name="dateClassFilter" class="form-control">
                                                <option value="">-- Semua Jadwal Kelas --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 my-1 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100"><i
                                                    class="fa fa-file-excel mr-2"></i>Export Excel</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-member-package">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Tanggal Mendaftar</th>
                                                <th>Nama Peserta</th>
                                                <th>Email</th>
                                                <th>Nomor Handphone</th>
                                                <th>Jadwal Kelas</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
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
    @push('javascript-bottom')
        @include('js.master.member.script')
        <script>
            dataTable();

            $('#packageFilter').select2();
            $('#dateClassFilter').select2();
        </script>
    @endpush
@endsection
