@extends('layouts.section')
@section('content')
    <div class="px-3 py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"></div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h3>Selamat Datang, <b>{{ auth()->user()->name }}</b> !</h3>
                    </div>

                    <!-- Daftar Quiz -->
                    <div class="col-md-6" style="border-right: 0.1rem solid gainsboro;">
                        <h4>Daftar Quiz</h4>
                        <div class="row">
                            <!-- Quiz 1 -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6>Quiz 1</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 780.000</p>
                                            <p><i class="fa fa-key text-warning"></i> 10 Kali Akses</p>
                                        </div>
                                        <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Voluptatem eius quasi debitis!</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary">Pesan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Quiz 2 -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6>Quiz 2</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 380.000</p>
                                            <p><i class="fa fa-key text-warning"></i> 2 Kali Akses</p>
                                        </div>
                                        <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Voluptatem eius quasi debitis!</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary">Pesan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Quiz 3 -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6>Quiz 3</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 480.000</p>
                                            <p><i class="fa fa-key text-warning"></i> 4 Kali Akses</p>
                                        </div>
                                        <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Voluptatem eius quasi debitis!</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary">Pesan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Quiz 4 -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6>Quiz 4</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 880.000</p>
                                            <p><i class="fa fa-key text-warning"></i> 9 Kali Akses</p>
                                        </div>
                                        <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Voluptatem eius quasi debitis!</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary">Pesan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Kelas -->
                    <div class="col-md-6" style="border-left: 0.1rem solid gainsboro;">
                        <h4>Daftar Kelas</h4>
                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Kelas 1</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 3.480.000</p>
                                        <p><i class="fa fa-key text-warning"></i> 30 Kali Akses</p>
                                    </div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aperiam iure quaerat quasi
                                        magni ullam rem officiis fugiat, adipisci architecto numquam!</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary">Daftar</a>
                                    </div>
                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4>Kelas 2</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 3.000.000</p>
                                        <p><i class="fa fa-key text-warning"></i> 20 Kali Akses</p>
                                    </div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aperiam iure quaerat quasi
                                        magni ullam rem officiis fugiat, adipisci architecto numquam!</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary">Daftar</a>
                                    </div>
                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4>Kelas 3</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="fs-6"><i class="fa fa-tags text-danger"></i> Rp. 4.480.000</p>
                                        <p><i class="fa fa-key text-warning"></i> 40 Kali Akses</p>
                                    </div>
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aperiam iure quaerat quasi
                                        magni ullam rem officiis fugiat, adipisci architecto numquam!</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary">Daftar</a>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
