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
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <ul>
                                    @php $no = 0; @endphp
                                    @foreach ($questions as $questionSet)
                                        @foreach ($questionSet as $question)
                                            @php $no++; @endphp
                                            <li class="list-unstyled">
                                                {{ $no }}. {{ $question->question }}
                                                <ol style="list-style-type: lower-alpha;">
                                                    @foreach ($question->quizAnswer as $answer)
                                                        <li>{{ $answer->answer }}</li>
                                                    @endforeach
                                                </ol>
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->

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
