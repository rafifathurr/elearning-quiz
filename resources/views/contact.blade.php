@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h3 class="font-weight-bold"><i class="fas fa-address-book mr-2 text-primary"></i>Contact
                                    Person
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
                                <div class="col-md-6 p-3 border-right">
                                    <h3 class="font-weight-bolder">Nomor WhatsApp</h3>
                                    <hr>
                                    <p>
                                        <i class="fab fa-whatsapp mr-2 text-success"></i>
                                        <span class="text-lg">0812-133-87-133 (John)</span>
                                    </p>
                                    <p>
                                        <i class="fab fa-whatsapp mr-2 text-success"></i>
                                        <span class="text-lg">0813-124-89-143 (Doe)</span>
                                    </p>
                                </div>
                                <div class="col-md-6 p-3 border-left">
                                    <h3 class="font-weight-bolder">Nomor Telepon</h3>
                                    <hr>
                                    <p>
                                        <i class="fas fa-phone mr-2 text-success"></i>
                                        <span class="text-lg">(021) 7711004 (John)</span>
                                    </p>
                                    <p>
                                        <i class="fas fa-phone mr-2 text-success"></i>
                                        <span class="text-lg">(021) 8781828 (Jane)</span>
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
