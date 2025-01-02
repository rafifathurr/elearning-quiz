@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Preview Quiz</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Pertanyaan: {{ $totalQuestions }}</h3>
                                <ul>
                                    @php $no = 1; @endphp
                                    @foreach ($shuffledQuestions as $question)
                                        <li class="list-unstyled">
                                            {{ $no++ }}.

                                            {!! $question->question !!}
                                            {!! $question->description !!}
                                            <ol style="list-style-type: lower-alpha;">
                                                @foreach ($question->quizAnswer as $answer)
                                                    <li>{{ $answer->answer ?? '' }}
                                                        @if (!is_null($answer->answer_image))
                                                            <img src="{{ asset($answer->answer_image) }}"
                                                                class="img-fluid mt-3" style="max-height: 10rem;">
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content -->
    </div>
    {{-- <script>
        // Mencegah refresh dengan menekan F5 atau tombol CTRL + R
        window.addEventListener('keydown', function(e) {
            if ((e.key === 'F5') || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault(); // Mencegah aksi default refresh
                alert("Halaman tidak dapat di-refresh");
            }
        });

        // Mencegah refresh dengan menekan tombol reload pada browser
        window.onbeforeunload = function() {
            return "Apakah Anda yakin ingin meninggalkan halaman ini?";
        };
    </script> --}}
@endsection
