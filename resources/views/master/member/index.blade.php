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
                                            <label for="packageFilter">Filter Nama Paket:</label>
                                            <select id="packageFilter" name="packageFilter" class="form-control">
                                                <option value="">-- Semua Paket --</option>
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 my-1">
                                            <label for="dateClassFilter">Filter Tanggal Kelas:</label>
                                            <select id="dateClassFilter" name="dateClassFilter" class="form-control">
                                                <option value="">-- Semua Jadwal Kelas --</option>
                                                @foreach ($dateClasses as $date)
                                                    <option value="{{ $date->id }}">{{ $date->name }}</option>
                                                @endforeach
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
                                                <th>Nama Peserta</th>
                                                <th>Jadwal Kelas</th>
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
