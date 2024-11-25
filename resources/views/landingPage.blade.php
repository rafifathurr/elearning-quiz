@extends('layouts.main')
@section('section')

    <body class="hold-transition layout-top-nav">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">E - Learning Quiz</a>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link font-weight-bold">Login <i class="fa fa-sign-in-alt"></i></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="py-3 px-3">
            <div class="row">
                <div class="col-md-8">
                    {{-- Carousel --}}
                    <div class="card">
                        <div class="card-body">
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100 img-carousel" style="max-height: 450px"
                                            src="{{ asset('dist/adminlte/img/photo1.png') }}" alt="First slide">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100 img-carousel" style="max-height: 450px"
                                            src="{{ asset('dist/adminlte/img/photo2.png') }}" alt="Second slide">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100 img-carousel" style="max-height: 450px"
                                            src="{{ asset('dist/adminlte/img/photo1.png') }}" alt="Third slide">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100 img-carousel" style="max-height: 450px"
                                            src="{{ asset('dist/adminlte/img/photo4.jpg') }}" alt="Fourth slide">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-custom-icon" aria-hidden="true">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-custom-icon" aria-hidden="true">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Daftar Quiz</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Akses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Quiz 1</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 780.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 10 Kali Akses
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Quiz 2</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 480.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 8 Kali Akses
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Quiz 3</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 880.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 12 Kali Akses
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Quiz 4</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 1.000.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 18 Kali Akses
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Quiz 5</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 2.050.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 20 Kali Akses
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->

                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Info Boxes Style 2 -->
                    <div class="info-box mb-3 bg-warning">
                        <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Quiz</span>
                            <span class="info-box-number">400</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->

                    <div class="info-box mb-3 bg-success">
                        <span class="info-box-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kelas</span>
                            <span class="info-box-number">90</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->

                    <div class="info-box mb-3 bg-danger">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pengguna</span>
                            <span class="info-box-number">300</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>



                    <!-- PRODUCT LIST -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kelas Terbaru</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('dist/adminlte/img/kelas1.jpg') }}" alt="Product Image"
                                            class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title">Kelas 1
                                            <span class="badge badge-warning float-right">Rp. 4.000.000</span></a>
                                        <span class="product-description">
                                            Lorem ipsum dolor sit.
                                        </span>
                                    </div>
                                </li>
                                <!-- /.item -->
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('dist/adminlte/img/kelas2.jpg') }}" alt="Product Image"
                                            class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title">Kelas 2
                                            <span class="badge badge-info float-right">Rp. 3.000.000</span></a>
                                        <span class="product-description">
                                            Lorem, ipsum dolor.
                                        </span>
                                    </div>
                                </li>
                                <!-- /.item -->
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('dist/adminlte/img/kelas3.jpg') }}" alt="Product Image"
                                            class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title">
                                            Kelas 3<span class="badge badge-danger float-right">
                                                Rp. 2.200.000
                                            </span>
                                        </a>
                                        <span class="product-description">
                                            Lorem ipsum dolor sit amet consectetur adipisicing.
                                        </span>
                                    </div>
                                </li>
                                <!-- /.item -->
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('dist/adminlte/img/kelas4.jpg') }}" alt="Product Image"
                                            class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title">Kelas 4
                                            <span class="badge badge-success float-right">Rp. 3.100.000</span></a>
                                        <span class="product-description">
                                            Lorem ipsum dolor sit.
                                        </span>
                                    </div>
                                </li>
                                <!-- /.item -->
                            </ul>
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </div>
    </body>
@endsection
