@extends('layouts.main')
@section('section')
    <style>
        /* Menimpa gaya tercoret bawaan */
        .todo-list>li.done .text {
            text-decoration: none;
            /* Hilangkan teks tercoret */
            color: black;
            /* Warna hijau */
            font-weight: bold;
        }

        /* Menambahkan efek visual pada elemen */
        .todo-list>li.done {
            background-color: lightgreen
        }

        .back-to-top {
            position: fixed;
            bottom: 50px;
            right: 30px;
            display: none;
            /* Tombol tidak muncul kecuali di-scroll */
            z-index: 999;
        }

        .back-to-top.show {
            display: block;
            /* Tampilkan tombol saat ada scroll */
        }
    </style>

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
                        <a href="{{ route('login') }}" class="nav-link font-weight-bold">Login <i
                                class="fa fa-sign-in-alt"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
        {{-- Carousel --}}
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/photo1.jpg') }}" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/photo2.jpg') }}" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/photo3.jpg') }}" alt="Third slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/photo4.jpg') }}" alt="Fourth slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-custom-icon" aria-hidden="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-custom-icon" aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div class="py-3 px-3">
            <div class="row mx-3 ">
                <div class="col-md-8">

                    {{-- Best Seller --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-success text-lg">
                                        Best Seller
                                    </div>
                                </div>
                                <img src="{{ asset('dist/adminlte/img/kelas1.jpg') }}" class="card-img-top" alt="Kelas A">
                                <div class="card-header">
                                    <h5>Kelas A</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 45+
                                            Materi
                                            Belajar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 30
                                            Hari
                                            Daily Quiz</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Soal
                                            HOTS
                                        </li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Hasil
                                            Ujian
                                            Langsung Keluar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Grup
                                            Belajar
                                            Premium</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-times text-danger"></i> Modul
                                            Pembelajaran</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-times text-danger"></i> Akses
                                            Selamanya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-success text-lg">
                                        Best Seller
                                    </div>
                                </div>
                                <img src="{{ asset('dist/adminlte/img/kelas2.jpg') }}" class="card-img-top"
                                    alt="Kelas A">
                                <div class="card-header">
                                    <h5>Kelas B</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 45+
                                            Materi
                                            Belajar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 30
                                            Hari
                                            Daily Quiz</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Soal
                                            HOTS
                                        </li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i>
                                            Hasil
                                            Ujian
                                            Langsung Keluar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Grup
                                            Belajar
                                            Premium</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i>
                                            Modul
                                            Pembelajaran</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-times text-danger"></i> Akses
                                            Selamanya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-success text-lg">
                                        Best Seller
                                    </div>
                                </div>
                                <img src="{{ asset('dist/adminlte/img/kelas3.jpg') }}" class="card-img-top"
                                    alt="Kelas A">
                                <div class="card-header">
                                    <h5>Kelas C</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 45+
                                            Materi
                                            Belajar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> 30
                                            Hari
                                            Daily Quiz</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Soal
                                            HOTS
                                        </li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i>
                                            Hasil
                                            Ujian
                                            Langsung Keluar</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i> Grup
                                            Belajar
                                            Premium</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i>
                                            Modul
                                            Pembelajaran</li>
                                        <li class="font-weight-normal mb-1"><i class="fas fa-check text-success"></i>
                                            Akses
                                            Selamanya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- Quiz --}}
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Daftar Quiz</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Akses</th>
                                            <th></th>
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
                                            <td><a href="#" class="btn btn-sm btn-primary">Daftar</a></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Quiz 2</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 480.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 8 Kali Akses
                                            </td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Daftar</a></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Quiz 3</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 880.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 12 Kali Akses
                                            </td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Daftar</a></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Quiz 4</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 1.000.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 18 Kali Akses
                                            </td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Daftar</a></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Quiz 5</td>
                                            <td><i class="fa fa-tags text-danger"></i> Rp. 2.050.000</td>
                                            <td>
                                                <i class="fa fa-key text-warning"></i> 20 Kali Akses
                                            </td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Daftar</a></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->

                    </div>

                    <!-- FAQ -->
                    <div class="col-12" id="accordion">
                        <h4>Pertanyaan Seputar Quiz</h4>
                        <div class="card card-primary card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        1. Bagaimana cara membeli paket di Elerning-Quiz?
                                    </h4>
                                </div>
                            </a>
                            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                <div class="card-body">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                                    dolor.
                                </div>
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        2. Materi apa saja yang tersedia dalam soal soal quiz?
                                    </h4>
                                </div>
                            </a>
                            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                                    ridiculus mus.
                                </div>
                            </div>
                        </div>
                        <div class="card card-warning card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        3. Apa kelebihan yang didapat dengan mengikuti try out quiz?
                                    </h4>
                                </div>
                            </a>
                            <div id="collapseThree" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat
                                    massa quis enim.
                                </div>
                            </div>
                        </div>
                        <div class="card card-warning card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        4. Jika mengalami kendala dalam pembelian paket apa yang harus dilakukan?
                                    </h4>
                                </div>
                            </a>
                            <div id="collapseFour" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                    <!-- Kelas LIST -->
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
                                            Lorem ipsum dolor sit amet consectetur.
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

                    <!-- TO DO List -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Pilihan Program
                            </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <ul class="todo-list" data-widget="todo-list">
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo1" id="todoCheck1" />
                                        <label for="todoCheck1"></label>
                                    </div>
                                    <span class="text">ASN</span>
                                </li>
                                <li>
                                    <!-- checkbox -->
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo1" id="todoCheck2" />
                                        <label for="todoCheck2"></label>
                                    </div>
                                    <!-- todo text -->
                                    <span class="text">Polisi Tamtama</span>
                                    <!-- Emphasis label -->

                                    <!-- General tools such as edit or delete-->
                                </li>
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo3" id="todoCheck3" />
                                        <label for="todoCheck3"></label>
                                    </div>
                                    <span class="text">Polisi Bintara</span>


                                </li>
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo3" id="todoCheck4" />
                                        <label for="todoCheck4"></label>
                                    </div>
                                    <span class="text">Polisi Perwira</span>


                                </li>
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo4" id="todoCheck5" />
                                        <label for="todoCheck5"></label>
                                    </div>
                                    <span class="text">TNI Tamtama</span>


                                </li>
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo5" id="todoCheck6" />
                                        <label for="todoCheck6"></label>
                                    </div>
                                    <span class="text">TNI Bintara</span>


                                </li>
                                <li>
                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="checkbox" value="" name="todo6" id="todoCheck7" />
                                        <label for="todoCheck7"></label>
                                    </div>
                                    <span class="text">TNI Perwira</span>


                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Contact Us --}}
                    <div class="card">
                        <div class="card-header">
                            <h5>Kontak Kita</h5>
                        </div>
                        <div class="card-body">
                            <!-- Google Maps iframe -->
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.147123000197!2d106.85284697409665!3d-6.244334161141676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3b95d1599b7%3A0x71c3550e06bcd528!2sKorlantas%20Polri%20SIM%20International!5e0!3m2!1sid!2sid!4v1732530968618!5m2!1sid!2sid"
                                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                            <!-- Kontak Details -->
                            <div class="mt-3">
                                <p><i class="fa fa-map-marker-alt text-danger"></i> Jl. Letjen M.T. Haryono No.37-38 8,
                                    RT.6/RW.2, Cikoko, Kec. Pancoran, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta
                                    12770</p>
                                <p><i class="fa fa-phone-alt text-success"></i> (021)7891232</p>
                                <p><i class="fa fa-envelope text-primary"></i> test@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button"
            aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const backToTopButton = document.querySelector('.back-to-top');

                backToTopButton.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah tindakan default anchor

                    // Scroll halus ke atas
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });

                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) { // Tampilkan tombol jika scroll lebih dari 300px
                        backToTopButton.classList.add('show');
                    } else {
                        backToTopButton.classList.remove('show');
                    }
                });
            });
        </script>

    </body>
@endsection
