@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @foreach ($quizes as $quiz)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold">{{ $quiz->typeQuiz->name }} </h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-2">{{ $quiz->name }}</h5>
                                    @php
                                        $currentDateTime = \Carbon\Carbon::now();
                                        $openQuizDateTime = $quiz->open_quiz
                                            ? \Carbon\Carbon::parse($quiz->open_quiz)
                                            : null;
                                        $closeQuizDateTime = $quiz->close_quiz
                                            ? \Carbon\Carbon::parse($quiz->close_quiz)
                                            : null;
                                    @endphp
                                    <div class="d-flex mb-2 card-text">
                                        <p class="mr-3 mb-0">
                                            <i class="bi bi-clock text-primary"></i> {{ $quiz->time_duration }} Menit
                                        </p>
                                        <p class="mb-0">
                                            @if (
                                                (!is_null($quiz->open_quiz) && $currentDateTime->lt($openQuizDateTime)) ||
                                                    (!is_null($quiz->close_quiz) && $currentDateTime->gt($closeQuizDateTime)))
                                                <span class="text-danger mr-3">Tutup</span>
                                            @else
                                                <span class="text-success mr-3">Buka</span>
                                            @endif

                                            @if (!is_null($quiz->open_quiz) && $currentDateTime->lt($openQuizDateTime))
                                                <span class="text-success">Buka
                                                    {{ $openQuizDateTime->format('d-m-Y H:i') }}</span>
                                            @elseif (!is_null($quiz->close_quiz) && $currentDateTime->lte($closeQuizDateTime))
                                                <span class="text-danger">Tutup
                                                    {{ $closeQuizDateTime->format('d-m-Y H:i') }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <p>{!! $quiz->description !!}</p>

                                </div>
                                <div class="card-footer">
                                    @if (
                                        (is_null($openQuizDateTime) && (is_null($closeQuizDateTime) || $currentDateTime->lte($closeQuizDateTime))) ||
                                            (!is_null($openQuizDateTime) &&
                                                !is_null($closeQuizDateTime) &&
                                                $currentDateTime->between($openQuizDateTime, $closeQuizDateTime)) ||
                                            (!is_null($openQuizDateTime) && is_null($closeQuizDateTime) && $currentDateTime->gte($openQuizDateTime)))
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.quiz.start', ['quiz' => $quiz->id]) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    @endif
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
