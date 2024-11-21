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
                            <div class="card-body p-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 text-center">
                                        <h3 class="font-weight-bold">{{ $result->quiz->name }}</h3>
                                        <h5 class="font-weight-medium">Total Point : {{ $result->total_score }} Point </h5>
                                        <h6>Waktu Submit: {{ $result->finish_time->format('H:i d-m-Y ') }}</h6>
                                        <div class="d-flex pt-3 justify-content-center">
                                            <div class="mx-2">
                                                <a href="{{ url('/') }}" class="btn btn-lg btn-danger"><i
                                                        class="fas fa-home mr-2"></i>Halaman Utama</a>
                                            </div>
                                        </div>
                                    </div>
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
            document.getElementById('mulaiKembali').addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pengalihan default

                // Hapus waktu dari localStorage ketika tombol "Mulai" diklik
                localStorage.removeItem('remainingTime');

                // Setelah menghapus, pindahkan halaman secara manual
                window.location.href = this.href;
            });
        </script>
    @endpush
@endsection
