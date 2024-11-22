@extends('layouts.section')

@section('content')
    <div class="py-4 px-3">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Review Riwayat Quiz - {{ $review->quiz->name }}</h5>
                                <h6><strong>Total Skor:</strong> {{ $review->total_score }}</h6>
                            </div>
                            <div class="card-body">
                                @foreach ($review->details as $detail)
                                    <p>{{ $detail->order }}. {{ $detail->resultQuestion->question }}</p>
                                    <ol style="list-style-type: lower-alpha;">
                                        @foreach ($detail->resultQuestion->quizAnswer as $answer)
                                            <li class="{{ $answer->is_answer ? 'text-success font-weight-bolder' : '' }}">
                                                {{ $answer->answer }}
                                                @if ($detail->answer == $answer->answer)
                                                    <span class="text-primary font-weight-bolder"> (Jawaban Anda)</span>
                                                @endif
                                                @if ($detail->answer == $answer->answer && $answer->is_answer)
                                                    <span class="btn btn-xs bg-success"><i class="fas fa-check"></i>
                                                    </span>
                                                @elseif ($detail->answer == $answer->answer && !$answer->is_answer)
                                                    <span class="btn btn-xs bg-danger"><i class="fas fa-times"></i>
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
