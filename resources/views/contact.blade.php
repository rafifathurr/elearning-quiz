@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h3 class="font-weight-bold"><i class="fas fa-address-book mr-2 text-primary"></i>Kontak Brata
                                    Cerdas
                                </h3>
                                <?php
                                $result = App\Models\Result::where('user_id', Auth::id())->whereNull('finish_time')->first();
                                $currentDateTime = \Carbon\Carbon::now();
                                ?>
                                @if ($result)
                                    <?php $startTime = \Carbon\Carbon::parse($result->start_time);
                                    $endTime = $startTime->copy()->addSeconds($result->time_duration); ?>
                                    @if ($currentDateTime->lte($endTime))
                                        <?php $remainingSeconds = $endTime->timestamp - $currentDateTime->timestamp; ?>
                                        @if ($result->quiz->type_aspect == 'kecermatan')
                                            <a href="{{ route('kecermatan.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) }}"
                                                id="mulai" class="btn btn-success btn-sm ml-3 ">Kembali Ke Test</a>
                                        @else
                                            <a href="{{ route('admin.quiz.getQuestion', ['result' => $result->id, 'remaining_time' => encrypt($remainingSeconds)]) }}"
                                                id="mulai" class="btn btn-success btn-sm ml-3 ">Kembali Ke Test</a>
                                        @endif
                                    @else
                                        <?php
                                        $total_score = App\Models\ResultDetail::where('result_id', $result->id)->sum('score');
                                        $result->update([
                                            'finish_time' => $endTime,
                                            'total_score' => $total_score,
                                        ]); ?>
                                    @endif
                                @endif

                            </div>
                            <hr>
                            <div class="row g-4">
                                <div class="col-md-6 p-3 ">
                                    <h3 class="font-weight-bolder">Nomor WhatsApp</h3>
                                    <hr>
                                    <p class="mb-3">
                                        <a href="https://wa.me/6285860571111" target="_blank"
                                            class="d-flex align-items-center text-success text-decoration-none">
                                            <i class="fab fa-whatsapp fa-2x mr-3 hover-scale"></i>
                                            <span class="h5 font-weight-bold">+62 858 6057 1111</span>
                                        </a>
                                    </p>
                                    <p>
                                        <a href="https://wa.me/6281315001480" target="_blank"
                                            class="d-flex align-items-center text-success text-decoration-none">
                                            <i class="fab fa-whatsapp fa-2x mr-3 hover-scale"></i>
                                            <span class="h5 font-weight-bold">+62 813 1500 1480</span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        <script>
            document.getElementById('mulai').addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.removeItem('remainingTime');
                window.location.href = this.href;
            });
        </script>
    @endpush
@endsection
