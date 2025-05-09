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
                                <h3 class="font-weight-bold">Daftar Test</h3>
                            </div>
                            <div class="card-body">
                                @hasanyrole('admin|question-operator')
                                    <div class="d-flex">
                                        <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus mr-1"></i>
                                            Tambah Test
                                        </a>
                                        <a href="{{ route('master.kecermatan.create') }}" class="btn btn-info btn-sm ml-3">
                                            <i class="fas fa-plus mr-1"></i>
                                            Tambah Test Kecermatan

                                        </a>
                                    </div>
                                @endhasanyrole
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url" value="{{ url()->current() }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Test</th>
                                                <th>Tipe Pertanyaan</th>
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
        <script>
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: $('#url').val(),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '5%'
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'type_aspect',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        class: 'text-center',
                        width: '15%'
                    }
                ]
            });

            function destroyRecord(id) {
                let token = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({
                    title: 'Apakah Anda Yakin Hapus Data?',
                    icon: 'question',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-primary mr-2 mb-3',
                        cancelButton: 'btn btn-danger mb-3',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalProcess();
                        $.ajax({
                            url: '{{ url('admin/quiz') }}/' + id,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                _token: token
                            },
                            success: function(data) {
                                location.reload();
                            },
                            error: function(xhr, error, code) {
                                swalError(error);
                            }
                        });
                    }
                })
            }
        </script>
    @endpush
@endsection
