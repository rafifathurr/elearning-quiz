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
                                <h3 class="font-weight-bold">Bank Soal</h3>
                            </div>
                            <div class="card-body">
                                @hasanyrole('admin|question-operator')
                                    <a href="{{ route('master.question.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Tambah Pertanyaan
                                    </a>
                                @endhasanyrole
                                <div class="form-group my-3">
                                    <label for="filter-type_aspect">Filter Tipe Aspek</label>
                                    <select id="filter-type_aspect" class="form-control w-25">
                                        <option value="">-- Pilih Tipe Aspek --</option>
                                        <option value="kecerdasan">Kecerdasan</option>
                                        <option value="kepribadian">Kepribadian</option>
                                    </select>
                                </div>

                                <div class="form-group my-3">
                                    <label for="filter-aspect">Filter Aspek</label>
                                    <select id="filter-aspect" class="form-control w-25">
                                        <option value="">-- Pilih Aspek --</option>
                                        <!-- Aspek akan diisi melalui JavaScript -->
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
