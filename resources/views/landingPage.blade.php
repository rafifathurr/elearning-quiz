@extends('layouts.main')
@section('section')
    <style>
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


        .custom-shape {
            display: inline-block;
            position: relative;
            padding: 10px 20px;
            font-weight: bold;
            clip-path: polygon(100% 0, 93% 50%, 100% 99%, 0% 100%, 7% 50%, 0% 0%)
        }

        .package .card {
            transition: transform 0.3s ease;
            /* Animasi untuk memperhalus perubahan ukuran */
        }

        .package .card:hover {
            transform: scale(1.05);
            /* Membesarkan ukuran card 5% */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            /* Menambahkan efek bayangan saat hover */
        }

        .tagline .card {
            border-radius: 2rem;
            background-color: #E3F2FD;
            border: 1px solid rgb(164, 164, 233);

        }

        .tagline .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        /* Navbar Default */
        #navbar {
            transition: all 0.4s ease;
            background-color: transparent;
            /* Transparan saat di posisi atas */
            z-index: 1000;
            position: relative;
            /* Tidak sticky secara default */
        }

        /* Efek Blur dan Sticky saat Scroll */
        #navbar.scrolled {
            position: fixed;
            /* Sticky hanya saat di-scroll */
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            /* Semi transparan */
            backdrop-filter: blur(10px);
            /* Efek blur */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* Bayangan halus */
            animation: slideDown 0.3s ease;
            /* Animasi muncul dari atas */
        }

        /* Animasi Navbar Muncul */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        /* Hover Efek */
        .nav-link {
            transition: color 0.3s ease, transform 0.2s;
        }

        .nav-link:hover {
            color: #007bff !important;
            transform: translateY(-2px);
        }


        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        @media (max-width: 768px) {
            .nav-mobile-adjust {
                margin-left: 1.5rem !important;
                /* Sama dengan ml-4 di Bootstrap */
                margin-right: 0 !important;
                /* Hilangkan margin kanan jika perlu */
            }
        }



        /* Bentuk Belah Ketupat */
    </style>

    <body class="hold-transition layout-top-nav">
        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white" id="navbar">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <img src="{{ asset('img/bclogo.png') }}" alt="Brata Cerdas Logo" class="brand-image"
                        style="max-width: 3rem;">
                    <span class="brand-text font-weight-bold text-secondary ml-2" style="opacity: .9;">BRATA CERDAS</span>

                    <ul class="navbar-nav ml-4">
                        <li class="nav-item"><a class="nav-link font-weight-bold" href="#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link font-weight-bold" href="#contact">Kontak</a></li>
                        <li class="nav-item"><a class="nav-link font-weight-bold" href="#package">Paket</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto nav-mobile-adjust">
                        @if (Auth::check())
                            <li class="nav-item"><a href="{{ route('home') }}"
                                    class="nav-link font-weight-bold">Dashboard</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link font-weight-bold" data-toggle="dropdown" href="#">
                                    <i class="far fa-user mr-2"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('logout') }}" class="dropdown-item"><i
                                            class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link font-weight-bold">
                                    <i class="fa fa-sign-in-alt mr-1"></i> Login
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>


            </div>
        </nav>




        {{-- Banner --}}
        <div class="home" id="home">
            <img class="d-block w-100 " style="max-height: 650px" src="{{ asset('dist/adminlte/img/bannerpol.jpg') }}">
        </div>


        {{-- Kenapa Harus Brata Cerdas --}}
        <div class="tagline">
            <div class="p-3 ">
                <h3 class="text-center font-weight-bold my-3">
                    Kenapa Harus Bratacerdas <span style="font-size: 2.4rem" class="text-blue"><i
                            class="fas fa-question"></i></span>
                </h3>
                <div class="row  px-4">
                    <div class="col-md-4 my-3">
                        <div class="card h-100 ">
                            <div class="card-body text-center ">
                                <img src="{{ asset('img/why1.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Bimbingan Lengkap</h4>
                                <p class="text-muted">Tersedia kelas dan bimbingan konsultasi secara online dan offline
                                    dengan Try Out berkala
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 ">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why2.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Try Out Mandiri</h4>
                                <p class="text-muted">Tersedia paket Try Out mandiri yang dapat dilakukan dimanapun dan
                                    kapanpun</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 ">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why3.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Pengajar Kompeten</h4>
                                <p class="text-muted">Dibimbing oleh tenaga pengajar yang kompeten di bidangnya</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 ">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why4.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Sistem Digital</h4>
                                <p class="text-muted">Menggunakan sistem digital selama periode bimbingan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 ">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why7.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Materi Terupdate</h4>
                                <p class="text-muted">Pembaharuan materi secara berkala</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100  ">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why8.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold text-blue">Kelas Representatif
                                </h4>
                                <p class="text-muted">Ruangan kelas yang nyaman dan representatif untuk pembelajaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Paket --}}
        <div class="package pt-4" id="package">
            <div class="py-3 px-3">
                @if ($type_package->isNotEmpty())
                    <h3 class="text-center font-weight-bold my-3">
                        Daftar <span class="custom-shape bg-gradient-lightblue">Paket</span>
                    </h3>
                    <div class="row mx-3 justify-content-center">
                        @foreach ($type_package as $type)
                            <div class="col-md-5 col-sm-6 col-12 mx-1 my-3"> {{-- Responsif di layar kecil --}}
                                <div class="card h-100 rounded-lg shadow-sm border-0">
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
                                                <p class="text-center text-muted">{{ $child->description ?? '' }}
                                                </p>
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
        </div>

        {{-- Contact Us --}}
        <div class="contact-us p-4 justify-content-center" id="contact">
            <div class="row justify-content-center align-items-center">
                <!-- Kolom Gambar -->
                <div class="col-md-5 text-center mt-4 mt-md-0">
                    <img src="{{ asset('img/contactbrata.jpg') }}" alt="Kontak Kami" class="img-fluid "
                        style="max-height: 400px; object-fit: cover;">
                </div>

                <!-- Kolom Nomor WhatsApp -->
                <div class="col-md-5 bg-light p-4 rounded shadow-sm">
                    <h3 class="font-weight-bold mb-3">
                        <i class="fas fa-address-book mr-2 text-primary"></i>Kontak Kita
                    </h3>

                    <p class="text-muted">Hubungi kami kapan saja melalui WhatsApp untuk informasi lebih
                        lanjut.</p>

                    <!-- Kontak WhatsApp 1 -->
                    <p class="mb-3">
                        <a href="https://wa.me/6285860571111" target="_blank"
                            class="d-flex align-items-center text-success text-decoration-none">
                            <i class="fab fa-whatsapp fa-2x mr-3 hover-scale"></i>
                            <span class="h5 font-weight-bold">+62 858 6057 1111</span>
                        </a>
                    </p>

                    <!-- Kontak WhatsApp 2 -->
                    <p>
                        <a href="https://wa.me/6281315001480" target="_blank"
                            class="d-flex align-items-center text-success text-decoration-none">
                            <i class="fab fa-whatsapp fa-2x mr-3 hover-scale"></i>
                            <span class="h5 font-weight-bold">+62 813 1500 1480</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- CSS Tambahan -->
        <style>
            .hover-scale:hover {
                transform: scale(1.2);
                transition: transform 0.3s ease-in-out;
            }
        </style>



        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button"
            aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>


        @push('javascript-bottom')
            @include('js.order.script')
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
                        if (window.scrollY > 300) { // Tampilkan tombol jika scroll lebih dari 250px
                            backToTopButton.classList.add('show');
                        } else {
                            backToTopButton.classList.remove('show');
                        }
                    });

                    const navbar = document.getElementById("navbar");
                    let lastScrollY = window.scrollY; // Menyimpan posisi scroll terakhir

                    window.addEventListener("scroll", function() {
                        const currentScroll = window.scrollY;

                        if (currentScroll > 50) {
                            navbar.classList.add("scrolled"); // Tambahkan efek sticky saat scroll
                            navbar.classList.remove("hide"); // Pastikan navbar terlihat saat scroll ke bawah
                        } else {
                            navbar.classList.remove("scrolled");
                            navbar.classList.remove("hide"); // Tampilkan navbar kembali saat di posisi atas
                        }

                        // Deteksi arah scroll untuk menyembunyikan atau menampilkan navbar
                        if (currentScroll > lastScrollY && currentScroll > 100) {
                            navbar.classList.add("hide"); // Sembunyikan saat scroll ke bawah
                        } else {
                            navbar.classList.remove("hide"); // Tampilkan saat scroll ke atas
                        }

                        lastScrollY = currentScroll; // Update posisi scroll terakhir
                    });
                });
            </script>
        @endpush

    </body>
@endsection
