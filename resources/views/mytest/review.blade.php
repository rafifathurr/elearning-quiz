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
                                <div class="row mt-3">
                                    <div class="col-md-5">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td class="text-right  bg-light">Waktu Mulai</td>
                                                <td>{{ \Carbon\Carbon::parse($review->start_time)->translatedFormat('l,  d F Y, H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right  bg-light">Waktu Selesai</td>
                                                <td>{{ \Carbon\Carbon::parse($review->finish_time)->translatedFormat('l,  d F Y, H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right  bg-light">Durasi Pengerjaan</td>
                                                <td>
                                                    @php
                                                        $startTime = \Carbon\Carbon::parse($review->start_time);
                                                        $finishTime = \Carbon\Carbon::parse($review->finish_time);
                                                        $duration = $finishTime->diff($startTime); // Menghitung selisih waktu
                                                    @endphp

                                                    {{ $duration->h != 0 ? $duration->h . ' jam' : '' }}
                                                    {{ $duration->i != 0 ? $duration->i . ' menit' : '' }}
                                                    {{ $duration->s . ' detik' }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right  bg-light">Total Skor</td>
                                                <td>{{ $review->total_score }}/{{ count($review->details) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        @foreach ($questionsPerAspect as $aspect)
                                            @if ($aspect['percentage'] >= 90)
                                                <p class="text-success">{{ $review->user->name }} sudah
                                                    <strong>baik</strong> dalam aspek
                                                    <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                </p>
                                            @elseif ($aspect['percentage'] < 90 && $aspect['percentage'] >= 80)
                                                <p class="text-success">{{ $review->user->name }} <strong>cukup
                                                        baik</strong> dalam aspek
                                                    <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                </p>
                                            @elseif ($aspect['percentage'] < 80 && $aspect['percentage'] >= 70)
                                                <p class="text-success">{{ $review->user->name }} <strong>cukup</strong>
                                                    dalam aspek
                                                    <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                </p>
                                            @elseif ($aspect['percentage'] < 70 && $aspect['percentage'] >= 50)
                                                <p style="color: orange">
                                                    {{ $review->user->name }} masih <strong>kurang</strong> dalam aspek
                                                    <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                </p>
                                            @elseif ($aspect['percentage'] < 50)
                                                <p class="text-danger">
                                                    {{ $review->user->name }} masih <strong>kurang sekali</strong> dalam
                                                    aspek
                                                    <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                </p>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach ($review->details->sortBy('order') as $detail)
                                    <table>
                                        <tr>
                                            <td>
                                                {{ $detail->order }}.
                                            </td>
                                            <td>
                                                {{ $detail->resultQuestion->direction_question }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>{!! $detail->resultQuestion->question ? $detail->resultQuestion->question : '' !!}</td>
                                        </tr>
                                    </table>

                                    @if (!is_null($detail->resultQuestion->attachment))
                                        <img src="{{ asset($detail->resultQuestion->attachment) }}"
                                            class="img img-fluid mb-4" style="max-height: 12rem;">
                                    @endif
                                    {!! $detail->resultQuestion->description !!}
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
