@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @foreach ($histories as $history)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold">{{ $history->quiz->typeQuiz->name }}</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-2">{{ $history->quiz->name }}</h5>
                                    <div class="card-text">
                                        <h5>Total skor: <span>{{ $history->total_score }}</span></h5>
                                        <p>Attempt ke-{{ $history->attempt_number }}</p>
                                        <a href="" class="btn btn-primary">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
    @endpush
@endsection
