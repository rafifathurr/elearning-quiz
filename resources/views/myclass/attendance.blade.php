@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('myclass.index') }}" class="btn btn-primary btn-sm my-2"><i
                                class="fas fa-arrow-left"></i> Kembali</a>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold">Absensi</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url" value="{{ url()->current() }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-attendance">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Waktu Absensi</th>
                                                <th>Status</th>
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
        @include('js.myclass.script')
        <script>
            dataTableAttendance();
        </script>
    @endpush
@endsection
