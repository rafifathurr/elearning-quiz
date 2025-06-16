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
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    My Voucher
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-myvoucher">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Nama Voucher</th>
                                                <th>Tipe Voucher</th>
                                                <th>Harga</th>
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
        @include('js.myvoucher.script')
        <script>
            dataTable();
        </script>

        <script>
            // Event listener tombol copy
            $(document).on('click', '.copy-btn', function() {
                let code = $(this).data('code');

                // Salin ke clipboard
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Disalin!',
                        text: 'Kode voucher berhasil disalin: ' + code,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Gagal menyalin', err);
                    Swal.fire('Error', 'Gagal menyalin kode voucher', 'error');
                });
            });
        </script>
    @endpush
@endsection
