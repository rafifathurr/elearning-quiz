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
                                        <h3 class="font-weight-bold">{{ $quiz->name }}</h3>
                                        <h5 class="font-weight-medium">Durasi : {{ $quiz->time_duration . ' Detik' }}</h5>
                                        <h5 class="font-weight-medium">
                                            {{ $totalQuestions > 0 ? 'Total Pertanyaan :' . $totalQuestions . ' Soal' : 'Jawab Pertanyaan Sebanyak-banyaknya' }}
                                        </h5>
                                        <div class="d-flex pt-3 justify-content-center">
                                            <div class="mx-2">
                                                @if (auth()->user()->hasRole('admin'))
                                                    <a href="{{ route('admin.quiz.index') }}" class="btn btn-lg btn-danger">
                                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                                    </a>
                                                @elseif (auth()->user()->hasRole('user'))
                                                    <a href="{{ url()->previous() }}" class="btn btn-lg btn-danger">
                                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="mx-2">
                                                @if ($quiz->type_aspect == 'kecermatan')
                                                    <a href="{{ route('kecermatan.play', ['quiz' => $quiz->id]) . '?order_detail_id=' . encrypt($orderDetailId) }}"
                                                        class="btn btn-lg btn-success" id="mulai">
                                                        <i class="fas fa-play mr-2"></i>Mulai Kecermatan
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.quiz.play', ['quiz' => $quiz->id]) . '?order_detail_id=' . encrypt($orderDetailId) }}"
                                                        class="btn btn-lg btn-success" id="mulai">
                                                        <i class="fas fa-play mr-2"></i>Mulai
                                                    </a>
                                                @endif
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
            document.getElementById('mulai').addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pengalihan default

                // Hapus waktu dari localStorage ketika tombol "Mulai" diklik
                localStorage.removeItem('remainingTime');
                localStorage.removeItem('waktuSisa');

                // Setelah menghapus, pindahkan halaman secara manual
                window.location.href = this.href;
            });
        </script>
    @endpush
@endsection
