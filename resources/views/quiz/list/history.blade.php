@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @forelse ($histories as $history)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold">{{ $history->quiz->typeQuiz->name }}</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-2">{{ $history->quiz->name }}</h5>
                                    <div class="card-text">
                                        <p>Dikerjakan Pada:
                                            {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y') }}
                                        </p>

                                        <h4 class="my-3">Total skor: <span>{{ $history->total_score }}</span></h4>
                                    </div>
                                </div>

                                <div class="card-footer bg-white ">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Attempt ke-{{ $history->attempt_number }}</p>
                                        <a href="{{ route('quiz.reviewQuiz', ['id' => $history->id]) }}"
                                            class="btn btn-sm btn-success">Review</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col text-center">
                            <div class="card py-4">
                                <h3>Belum Ada Riwayat Quiz</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center">
                    {{ $histories->links() }}
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
    @endpush
@endsection
