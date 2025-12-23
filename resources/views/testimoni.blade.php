@extends('layouts.main')
@section('section')
    <style>
        .nav-link {
            transition: color 0.3s ease, transform 0.2s;
        }

        .nav-link:hover {
            color: #007bff !important;
            transform: translateY(-2px);
        }


        .testimoni-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .testimoni-card {
            background: #fff;
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .testimoni-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
        }

        .testimoni-card video {
            width: 100%;
            aspect-ratio: 9 / 16;
            object-fit: cover;
            border-radius: 14px;
        }

        .testimoni-info {
            margin-top: 16px;
        }

        .testimoni-info h5 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .testimoni-info span {
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .nav-mobile-adjust {
                margin-left: 1.5rem !important;
                /* Sama dengan ml-4 di Bootstrap */
                margin-right: 0 !important;
                /* Hilangkan margin kanan jika perlu */
            }

            .testimoni-section {
                padding: 50px 0;
            }
        }
    </style>

    <body class="hold-transition layout-top-nav">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white" id="navbar">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <a href="{{ route('landingPage') }}" class="navbar-brand d-flex align-items-center">
                        <img src="{{ asset('img/bclogo.png') }}" alt="Brata Cerdas Logo" class="brand-image"
                            style="max-width: 3rem;">

                        <span class="brand-text font-weight-bold text-secondary ml-2" style="opacity: .9;">
                            BRATA CERDAS
                        </span>
                    </a>


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

        <section class="testimoni-section">
            <div class="container">

                <!-- Judul -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Apa Kata Mereka?</h2>
                    <p class="text-muted">
                        Testimoni langsung dari pengguna yang telah merasakan manfaat layanan kami
                    </p>
                </div>

                <!-- Video Testimoni -->
                <div class="row justify-content-center g-4">

                    <!-- Video 1 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="testimoni-card">
                            <video controls playsinline>
                                <source src="{{ asset('dist/adminlte/img/testi_1.mp4') }}" type="video/mp4">
                                Browser tidak mendukung video.
                            </video>

                            <div class="testimoni-info">
                                <h5>Angga Ditri Pratama</h5>
                                <span>Akpol</span>
                            </div>
                        </div>
                    </div>

                    <!-- Video 2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="testimoni-card">
                            <video controls playsinline>
                                <source src="{{ asset('dist/adminlte/img/testi_2.mp4') }}" type="video/mp4">
                                Browser tidak mendukung video.
                            </video>

                            <div class="testimoni-info">
                                <h5>Wahyu Bryant Villareal</h5>
                                <span>Akpol</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </body>
    @include('layouts.footer')
@endsection
