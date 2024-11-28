@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Test</h1>
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
                                <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah Test
                                </a>
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
