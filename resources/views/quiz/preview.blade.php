@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Quiz</h1>
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
                                    <?php $no = 0; ?>
                                    @foreach ($questions as $questionSet)
                                        @foreach ($questionSet as $question)
                                            <?php
                                            $no++; ?>
                                            <p>{{ $no }}. {{ $question->question }}</p>
                                            <ul>
                                                @foreach ($question->quizAnswer as $answer)
                                                    <li>{{ $answer->answer }}</li>
                                                @endforeach
                                            </ul>
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
@endsection
