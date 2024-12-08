@extends('layouts.section')

@section('content')
    <div class="py-4 px-3">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary mb-2">Kembali</a>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="font-weight-bold">Review Riwayat Test - {{ $review->quiz->name }}</h5>
                                <h6 class="font-weight-bolder">Persentase Benar:
                                    {{ ($review->total_score / $review->details->count()) * 100 . '%' }}
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($review->details->sortBy('order') as $detail)
                                    <p>{{ $detail->order }}. {{ $detail->resultQuestion->question }}
                                        @if (is_null($detail->answer))
                                            <span class="ml-2 text-danger font-weight-light">
                                                <i class="fas fa-times">
                                                    Tidak Menjawab
                                                </i>
                                            </span>
                                        @endif
                                    </p>
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
