@extends('layouts.main')
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
                                        <h5 class="font-weight-medium">Durasi : {{ $quiz->time_duration . ' Menit' }}</h5>
                                        <h5 class="font-weight-medium">Total Pertanyaan :
                                            {{ count($quiz->quizQuestion) . ' Soal' }}</h5>
                                        <h5 class="font-weight-medium">Total Pencapaian Poin :
                                            {{ '100 Poin' }}</h5>
                                        <div class="d-flex pt-3 justify-content-center">
                                            <div class="mx-2">
                                                <a href="{{route('admin.quiz.index') }}" class="btn btn-lg btn-danger"><i
                                                        class="fas fa-arrow-left mr-2"></i>Kembali</a>
                                            </div>
                                            <div class="mx-2">
                                                <a href="{{ route('admin.quiz.play', ['quiz' => $quiz->id]).'?q=1' }}"
                                                    class="btn btn-lg btn-success"><i class="fas fa-play mr-2"></i>Mulai</a>
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
    @endpush
@endsection
