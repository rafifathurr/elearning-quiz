@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @forelse ($quizes as $quiz)
                        <div class="col-md-4">
                            <div class="card h-100">
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
                                            <i class="bi bi-clock text-primary"></i>
                                            @if ($quiz->time_duration >= 3600)
                                                {{ floor($quiz->time_duration / 3600) }} jam
                                                {{ floor(($quiz->time_duration % 3600) / 60) }} menit

                                                {{ $quiz->time_duration % 60 ? $quiz->time_duration % 60 . 'detik' : '' }}
                                            @elseif ($quiz->time_duration >= 60)
                                                {{ floor($quiz->time_duration / 60) }} menit
                                                {{ $quiz->time_duration % 60 }} detik
                                            @else
                                                {{ $quiz->time_duration }} detik
                                            @endif
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
                                <div class="card-footer bg-white my-3">
                                    <div class="d-flex">

                                        <a href="{{ route('admin.quiz.play', ['quiz' => $quiz->id]) }}"
                                            class="btn btn-success" id="mulai"><i class="fas fa-play mr-2"></i>Mulai</a>
                                    </div>

                                </div>

                                {{-- <div class="card-footer bg-white my-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold">Sisa Akses:
                                            {{ count($quiz->quizAuthenticationAccess->whereNull('deleted_at')) }}</span>
                                        @if ((is_null($openQuizDateTime) && (is_null($closeQuizDateTime) || $currentDateTime->lte($closeQuizDateTime))) || (!is_null($openQuizDateTime) && !is_null($closeQuizDateTime) && $currentDateTime->between($openQuizDateTime, $closeQuizDateTime)) || (!is_null($openQuizDateTime) && is_null($closeQuizDateTime) && $currentDateTime->gte($openQuizDateTime)))
                                            <button data-toggle="modal" data-target="#modal-{{ $quiz->id }}"
                                                class="btn btn-sm btn-success"
                                                @if (count($quiz->quizAuthenticationAccess->whereNull('deleted_at')) < 1) disabled @endif>
                                                <i class="fas fa-play mr-2"></i>Mulai
                                            </button>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        {{-- <div id="modal-{{ $quiz->id }}" class="modal fade" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('quiz.auth') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Login {{ $quiz->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="code_access">Kode Akses</label>
                                                <input type="text" class="form-control" name="code_access"
                                                    id="code_access" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password" id="password"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                    @empty
                        <div class="col text-center ">
                            <div class="card  py-4">
                                <h3>Belum Ada Daftar Quiz</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $quizes->links() }}
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
        <script>
            localStorage.removeItem('remainingTime');
        </script>
    @endpush
@endsection
