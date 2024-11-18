@extends('layouts.section')

@section('content')
    <div class="container">
        <h2>Detail History Quiz - {{ $review->quiz->name }}</h2>
        <p><strong>Total Skor:</strong> {{ $review->total_score }}</p>

        <div class="questions-list">
            @foreach ($review->quiz->quizQuestion as $question)
                <h5>{{ $question->order }} . {{ $question->question }}</h5>
                <ul>
                    @foreach ($question->quizAnswer as $answer)
                        <li>
                            {{ $answer->answer }}
                            @php
                                // Mencari jawaban pengguna yang sesuai dari relasi `quizUserAnswer` melalui `quizAnswerResult`
                                $userAnswerResult = $review->quizAnswerResult
                                    ->where('quiz_user_result_id', $review->id)
                                    ->firstWhere('quizUserAnswer.quiz_answer_id', $answer->id);
                            @endphp
                            @if ($userAnswerResult)
                                <span>Jawaban Anda</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <a href="{{ route('quiz.historyQuiz') }}" class="btn btn-secondary mt-3">Kembali Ke History</a>
    </div>
@endsection
