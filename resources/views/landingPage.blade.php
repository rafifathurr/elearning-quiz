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

        .header-shape {
            display: inline-block;
            position: relative;
            padding: 12px 40px;
            font-weight: bold;
            clip-path: polygon(0% 0%, 95% 0%, 100% 50%, 95% 100%, 0% 100%);
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
            border-radius: 1.5rem;


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
            z-index: 99999;
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

        /* .select2-container {
                                                                                                                    z-index: 9999 !important;
                                                                                                                    margin-bottom: 1rem !important; */
        /* Menjamin dropdown tampil di atas modal */
        /* } */

        .select2-container .select2-selection--multiple {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border: 1px solid #94a3b8;
            border-radius: 10px;
            min-height: 42px;
            color: #0f172a;
            font-weight: 500;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(2, 132, 199, 0.08);
            padding: 4px 8px;
        }

        .select2-container--default .select2-selection--multiple:hover,
        .select2-container--default .select2-selection--multiple:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.2);
        }


        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0284c7;
            border: none;
            padding: 4px 12px;
            border-radius: 6px;
            margin: 3px 4px 0 0;
            font-size: 13px;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice span,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            color: #fff !important;
        }




        .swal2-popup {
            z-index: 1050 !important;
            /* Pastikan popup Swal berada di bawah dropdown */
        }

        .swal2-popup .select2-selection {
            margin-bottom: 1rem !important;
            /* Menambah jarak di dalam dropdown */
        }

        /* Bentuk Belah Ketupat */
    </style>

    <body class="hold-transition layout-top-nav">

        <!-- Modal untuk Video -->
        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"
                    style="border-radius: 10px; background-color: #FFF8E1; color: #333; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                    <div class="modal-header"
                        style="background-color: #1976D2; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h5 class="modal-title font-weight-bold" id="videoModalLabel" style="color: #f2ab1a;">Brata
                            Cerdas
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                            style="font-size: 24px; background: none; border: none; transition: 0.3s;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Embed Video -->
                        <video id="videoFrame" width="100%" height="400px" controls autoplay muted
                            style="border-radius: 8px;">
                            <source src="{{ asset('img/videoBrata2.mp4') }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                </div>
            </div>
        </div>

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
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" href="#" data-toggle="modal"
                                data-target="#videoModal">
                                E-Flyer
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto nav-mobile-adjust">
                        @if (Auth::check())
                            <li class="nav-item"><a href="{{ route('home') }}"
                                    class="nav-link font-weight-bold">Dashboard</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <ul class=" navbar-nav  ml-auto nav-mobile-adjust">
                    @if (Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link font-weight-bold" data-toggle="dropdown" href="#">
                                <i class="far fa-user mr-2"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" onclick="logoutAndRemoveToken()" class="dropdown-item"><i
                                        class="fas fa-sign-out-alt mr-2"></i>
                                    Logout</a>
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
        </nav>




        {{-- Banner --}}
        <div class="home" id="home">
            <img class="d-block w-100 " style="max-height: 650px" src="{{ asset('dist/adminlte/img/bannerpol.jpg') }}">
        </div>


        {{-- Kenapa Harus Brata Cerdas --}}
        <div class="tagline">
            <div class="p-3 ">
                <h3 class="text-center font-weight-bold my-3">
                    <span class="header-shape bg-gradient-lightblue"> Kenapa Harus Brata Cerdas
                    </span>
                    <span style="font-size: 2.4rem" class="text-blue"><i class="fas fa-question"></i></span>
                </h3>
                <div class="row  px-4">
                    <div class="col-md-4 my-3">
                        <div class="card h-100 " style="background-color: #FFECB3;">
                            <div class="card-body text-center ">
                                <img src="{{ asset('img/why11.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Bimbingan Lengkap</h4>
                                <p style="color: #388E3C">Brata Cerdas menyediakan kelas ONLINE dan OFFLINE untuk bimbingan
                                    dan konsultasi dengan Try Out berkala

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 " style="background-color: #C5E1A5;">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why12.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Try Out Mandiri</h4>
                                <p style="color: #33691E">Brata Cerdas memberikan paket Try Out mandiri yang dapat
                                    dilakukan
                                    dimanapun dan kapanpun
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 " style="background-color: #1976D2">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why13.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Pengajar Kompeten</h4>
                                <p style="color: #E3F2FD">Brata Cerdas bekerja sama dengan tenaga pengajar yang kompeten di
                                    bidangnya
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 " style="background-color: #C5E1A5;">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why14.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Sistem Digital</h4>
                                <p style="color: #33691E">Brata Cerdas membangun Sistem Digital sebagai platform yang
                                    digunakan selama bimbingan
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100 " style="background-color: #1976D2">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why17.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Materi Terupdate</h4>
                                <p style="color: #E3F2FD">Brata Cerdas memperbaharui materi secara berkala
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-3">
                        <div class="card h-100  " style="background-color: #FFECB3;">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/why18.png') }}" class="img-fluid" style="max-height: 250px">
                                <h4 class="font-weight-bold" style="color: #f2ab1a;">Kelas Representatif
                                </h4>
                                <p style="color: #388E3C">Brata Cerdas menyiapkan kelas yang nyaman dan representatif untuk
                                    proses bimbingan dan konsultasi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- TRY OUT --}}
        @if ($tryOutPackages->isNotEmpty())
            <h3 class="text-center font-weight-bold my-3">
                Program <span class="custom-shape bg-gradient-lightblue">TRY OUT</span>
            </h3>
            <div class="row mx-3 justify-content-center">
                @foreach ($tryOutPackages as $type)
                    <div class="col-12 mx-1 my-3">
                        <div class="card h-100 rounded-lg shadow-sm border-0">
                            <div class="card-header bg-gradient-lightblue text-center">
                                <h5 class="font-weight-bold text-white">{{ $type->name }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">{{ $type->description ?? '' }}</p>
                                <div class="row justify-content-center">
                                    @foreach ($type->package as $package)
                                        <div class="col-md-5 col-sm-6 col-12 my-2">
                                            @include('master.package_payment.package_list', [
                                                'packages' => [$package],
                                                'showMeta' => false,
                                            ])
                                        </div>
                                    @endforeach
                                </div>


                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- OTHER PACKAGES --}}
        @if ($otherPackages->isNotEmpty())
            <h3 class="text-center font-weight-bold my-3">
                Paket <span class="custom-shape bg-gradient-lightblue">Kelas</span>
            </h3>

            <div class="d-flex justify-content-center m-3">
                <div class="col-4">
                    <label>Aspek</label>
                    <select class="form-control select2" id="filterAspek" data-placeholder="Semua Aspek" multiple>
                        <option value="Psikologi">Psikologi</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Jasmani">Jasmani</option>
                    </select>
                </div>
                <div class="col-4">
                    <label>Pendidikan</label>
                    <select class="form-control select2" id="filterJenis" data-placeholder="Semua Pendidikan" multiple>
                        @foreach ($otherPackages->pluck('jenis')->unique() as $jenis)
                            <option value="{{ $jenis }}">{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <label>Sesi</label>
                    <select class="form-control select2" id="filterSesi" data-placeholder="Semua Sesi" multiple>
                        <option value="Online">Online</option>
                        <option value="Offline">Offline</option>
                    </select>
                </div>
            </div>

            <div class="row mx-3 justify-content-center">
                @foreach ($otherPackages as $package)
                    <div class="col-md-5 col-sm-6 col-12 my-2 mx-1" data-aspek="{{ strtolower($package->aspek ?? '') }}"
                        data-sesi="{{ strtolower($package->sesi ?? '') }}"
                        data-jenis="{{ strtolower($package->jenis ?? '') }}">
                        @include('master.package_payment.package_list', [
                            'package' => $package,
                            'showMeta' => true,
                        ])
                    </div>
                @endforeach
            </div>
        @endif



        {{-- Contact Us --}}
        <div class="contact-us p-4 justify-content-center" id="contact">
            <div class="row justify-content-center align-items-center">
                <!-- Kolom Gambar -->
                <div class="col-md-5 text-center mt-4 mt-md-0">
                    <img src="{{ asset('img/contactbrata.jpg') }}" alt="Kontak Kami" class="img-fluid "
                        style="max-height: 400px; object-fit: cover;">
                </div>

                <!-- Kolom Nomor WhatsApp -->
                <div class="col-md-5  p-4 ">
                    <h3 class="font-weight-bold mb-3">
                        <i class="fas fa-address-book mr-2 text-primary"></i>Kontak Brata Cerdas
                    </h3>

                    <p>Hubungi Brata Cerdas kapan saja melalui WhatsApp untuk informasi bimbingan dan konsultasi lebih
                        lanjut.</p>

                    <!-- Kontak WhatsApp 1 -->
                    <p class="mb-3">
                        <a href="https://wa.me/6281318826787" target="_blank"
                            class="d-flex align-items-center text-success text-decoration-none">
                            <i class="fab fa-whatsapp fa-2x mr-3 hover-scale"></i>
                            <span class="h5 font-weight-bold">+62 813 1882 6787</span>
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

        @auth
            <!-- Hapus Token Logout -->
            <script>
                function logoutAndRemoveToken() {
                    const fcmToken = localStorage.getItem('fcm_token');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (fcmToken) {
                        fetch("/hapus-token", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                token: fcmToken
                            })
                        }).finally(() => {
                            // Setelah selesai, redirect ke logout
                            window.location.href = "/logout";
                        });
                    } else {
                        window.location.href = "/logout";
                    }
                }
            </script>
        @endauth

        @push('javascript-bottom')
            @include('js.order.script')
            <!-- Script Filter Paket -->
            <script>
                $(function() {
                    $('.select2').select2({
                        allowClear: true,
                        width: '100%'
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    function applyFilter() {
                        const aspekValues = $('#filterAspek').val()?.map(v => v.toLowerCase()) || [];
                        const jenisValues = $('#filterJenis').val()?.map(v => v.toLowerCase()) || [];
                        const sesiValues = $('#filterSesi').val()?.map(v => v.toLowerCase()) || [];

                        document.querySelectorAll('[data-aspek]').forEach(pkg => {
                            let aspekMatch = aspekValues.length === 0 || aspekValues.includes(pkg.dataset.aspek);
                            let jenisMatch = jenisValues.length === 0 || jenisValues.includes(pkg.dataset.jenis);
                            let sesiMatch = sesiValues.length === 0 || sesiValues.includes(pkg.dataset.sesi);

                            pkg.style.display = (aspekMatch && jenisMatch && sesiMatch) ? '' : 'none';
                        });
                    }

                    $('#filterAspek, #filterJenis, #filterSesi').on('change', applyFilter);
                });
            </script>

            <!-- Script Back to Top & Navbar -->
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

            <!-- Script Modal Video -->
            <script>
                $(document).ready(function() {
                    var shouldShowModal = @json(!Auth::check());

                    // Jika user belum login, tampilkan modal setelah 2 detik
                    // if (shouldShowModal) {
                    // setTimeout(function() {
                    // $('#videoModal').modal('show');
                    // }, 2000);
                    // }

                    // Mulai video saat modal dibuka
                    $('#videoModal').on('shown.bs.modal', function() {
                        var video = document.getElementById("videoFrame");
                        video.currentTime = 0; // Pastikan video mulai dari awal
                        video.play(); // Putar video saat modal muncul
                    });

                    // Hentikan video saat modal ditutup
                    $('#videoModal').on('hidden.bs.modal', function() {
                        var video = document.getElementById("videoFrame");
                        video.pause(); // Hentikan video
                        video.currentTime = 0; // Reset video ke awal
                    });
                });
            </script>
        @endpush

    </body>
@endsection
