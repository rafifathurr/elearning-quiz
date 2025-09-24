@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Modal untuk Video -->
                <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-lightblue">
                                <h5 class="modal-title font-weight-bold text-white" id="videoModalLabel">Tutorial
                                    Mengerjakan Test
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                                    style="font-size: 24px; background: none; border: none; transition: 0.3s;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <!-- Embed Video -->
                                <video id="videoFrame" width="100%" controls autoplay muted style=" max-height: 400px; ">
                                    <source src="{{ asset('img/tutorial_mengerjakan_test.mp4') }}" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    My Test
                                </h3>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#videoModal">
                                    <i class="fas fa-book-reader mr-1"></i>
                                    Panduan Mengerjakan Test
                                </button>
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-mytest">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Nama Test</th>
                                                <th>Tipe Test</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

    </div>
    @push('javascript-bottom')
        @include('js.mytest.script')
        <script>
            dataTable();
        </script>

        <script>
            $(document).ready(function() {
                var shouldShowModal = @json(!Auth::check());



                // Mulai video saat modal dibuka
                $('#videoModal').on('shown.bs.modal', function() {
                    var video = document.getElementById("videoFrame");
                    video.currentTime = 0;
                    video.play();
                });

                // Hentikan video saat modal ditutup
                $('#videoModal').on('hidden.bs.modal', function() {
                    var video = document.getElementById("videoFrame");
                    video.pause();
                    video.currentTime = 0;
                });
            });
        </script>
    @endpush
@endsection
