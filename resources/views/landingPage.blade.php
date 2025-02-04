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

        .list-unstyled {
            list-style-type: none;
            padding-left: 0;
        }

        .list-unstyled .package-item {
            position: relative;
            padding-left: 30px;
            /* Space for the icon */
        }

        .list-unstyled .package-item::before {
            content: "\f00c";
            /* FontAwesome checkmark icon */
            font-family: "Font Awesome 5 Free";
            /* Font Awesome family */
            font-weight: 900;
            /* Required for solid icons */
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: green;
            /* Icon color */
        }

        .custom-shape {
            display: inline-block;
            position: relative;
            padding: 10px 20px;
            font-weight: bold;
            clip-path: polygon(100% 0, 93% 50%, 100% 99%, 0% 100%, 7% 50%, 0% 0%)
        }

        .card {
            transition: transform 0.3s ease;
            /* Animasi untuk memperhalus perubahan ukuran */
        }

        .card:hover {
            transform: scale(1.05);
            /* Membesarkan ukuran card 5% */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            /* Menambahkan efek bayangan saat hover */
        }

        /* Bentuk Belah Ketupat */
    </style>

    <body class="hold-transition layout-top-nav">
        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <img src="{{ asset('img/bclogo.png') }}" alt="AdminLTE Logo" class="brand-image  "
                        style="max-width: 3rem;">
                    <span class="brand-text font-weight-bold text-secondary" style="opacity: .8">BRATA CERDAS</span>

                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

                    @if (Auth::check())
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link font-weight-bold">
                                <i class="fa fa-home mr-2"></i>Home </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link font-weight-bold" data-toggle="dropdown" href="#">
                                <i class="far fa-user mr-2"></i>
                                {{ Auth::user()->name }}

                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <a href="{{ route('logout') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout

                                </a>

                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link font-weight-bold">Login <i
                                    class="fa fa-sign-in-alt"></i></a>
                        </li>
                    @endif

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
                        src="{{ asset('dist/adminlte/img/ban1.png') }}" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/ban2.png') }}" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/ban3.png') }}" alt="Third slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 img-carousel" style="max-height: 650px"
                        src="{{ asset('dist/adminlte/img/ban4.png') }}" alt="Fourth slide">
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
            @if ($type_package->isNotEmpty())
                <h3 class="text-center font-weight-bold my-3">
                    Daftar <span class="custom-shape bg-gradient-lightblue">Paket</span>
                </h3>
                <div class="row mx-3 justify-content-center">
                    @foreach ($type_package as $type)
                        <div class="col-md-5 col-sm-6 col-12 mx-1 my-3"> {{-- Responsif di layar kecil --}}
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-gradient-lightblue text-center">
                                    <h5 class="font-weight-bold text-white">{{ $type->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-center text-muted">{{ $type->description ?? '' }}</p>

                                    {{-- Paket dari parent --}}
                                    @include('master.package_payment.package_list', [
                                        'packages' => $type->package,
                                    ])

                                    {{-- Paket dari children --}}
                                    @foreach ($type->children as $child)
                                        <div class="border rounded-lg p-2 mx-2 my-3 bg-light">
                                            <h4 class="text-center font-weight-bold text-primary">
                                                {{ $child->name }}
                                            </h4>
                                            @include('master.package_payment.package_list', [
                                                'packages' => $child->package,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            @else
                <h3 class="text-center font-weight-bold my-3">
                    Belum Ada Paket
                </h3>
            @endif

        </div>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button"
            aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>


        @push('javascript-bottom')
            @include('js.order.script')
            <script>
                $(document).ready(function() {
                    $('#table-test').DataTable({
                        language: {
                            lengthMenu: "" //ngilangin sort diatas
                        },
                        columnDefs: [{
                            orderable: false,
                            targets: [1, 3, 4]
                        }],
                        pageLength: 4
                    });
                });
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
        @endpush

    </body>
@endsection
