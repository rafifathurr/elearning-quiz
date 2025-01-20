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

        .equal-height {
            min-height: 350px;
            /* Sesuaikan nilai sesuai kebutuhan */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
                    <img src="{{ asset('img/brata.png') }}" alt="AdminLTE Logo" class="brand-image  "
                        style="max-width: 3rem;">
                    <span class="brand-text font-weight-bold text-secondary" style="opacity: .8">BRATA CERDAS</span>
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
                {{-- Best Seller --}}
                <div class="row mb-4">

                    @foreach ($best_seller as $class)
                        <div class="col-md-3 my-2">
                            <div class="card h-100 equal-height">
                                <div class="card-header bg-gradient-lightblue">
                                    <h5>{{ $class->name }}</h5>
                                </div>
                                <div class="card-body">

                                    @if ($class->price == 0)
                                        <h5 class="mb-3 text-success"><i class="fa fa-gift "></i>
                                            Gratis</h5>
                                    @else
                                        <h5 class="mb-3"><i class="fa fa-tags text-danger"></i>
                                            {{ 'Rp. ' . number_format($class->price, 0, ',', '.') }}</h5>
                                    @endif

                                    @if (isset($class->class) && $class->class > 0)
                                        <h6 class="font-weight-bolder text-cyan "><i class="fas fa-chalkboard-teacher "></i>
                                            <span class="ml-1">
                                                {{ $class->class }} Kali Pertemuan
                                            </span>
                                        </h6>
                                    @endif
                                    <ul class="list-unstyled">
                                        @foreach ($class->packageTest as $package)
                                            <li class="package-item font-weight-normal mb-1">
                                                {{ $package->quiz->name . ' (' . $package->quiz->type_aspect . ')' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary "> <i
                                                class="fas fa-shopping-cart"></i> Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>


        @push('javascript-bottom')
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
