@extends('layouts.main')
@section('section')
    <style>
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
            .testimoni-section {
                padding: 50px 0;
            }
        }
    </style>

    <body class="hold-transition layout-top-nav">
        @include('layouts.navbar_landing_page')

        <section class="testimoni-section">
            <div class="container">

                <!-- Judul -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Apa Kata Mereka?</h2>
                    <p class="text-muted">
                        Testimoni langsung dari peserta bimbingan yang telah merasakan manfaat layanan kami
                    </p>
                </div>

                <!-- Video Testimoni -->
                <div class="row justify-content-center g-4">
                    @foreach ($testimoni as $testi)
                        <div class="col-md-6 col-lg-4">
                            <div class="testimoni-card">
                                <video playsinline controls controlslist="nodownload nofullscreen" disablepictureinpicture>
                                    <source src="{{ asset('dist/adminlte/img/' . $testi['video']) }}" type="video/mp4">
                                    Browser tidak mendukung video.
                                </video>

                                <div class="testimoni-info">
                                    <h5>{{ $testi['nama'] }}</h5>
                                    <span>{{ $testi['pendidikan'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    </body>
    @include('layouts.footer')
@endsection
