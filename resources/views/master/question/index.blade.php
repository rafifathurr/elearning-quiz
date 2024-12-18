@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Bank Soal</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('master.question.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah Pertanyaan
                                </a>
                                <div class="form-group my-3">
                                    <label for="filter-aspect">Filter Aspek</label>
                                    <select id="filter-aspect" class="form-control w-25">
                                        <option value="">-- Pilih Aspek --</option>
                                        @foreach ($aspects as $aspect)
                                            <option value="{{ $aspect->id }}">{{ $aspect->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-question">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pertanyaan</th>
                                                <th>Aspek Pertanyaan</th>
                                                <th>Deskripsi</th>
                                                <th>Level</th>
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
        @include('js.master.question.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
