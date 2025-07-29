@extends('layouts.section')
@section('content')
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
            clip-path: polygon(100% 0, 93% 50%, 100% 99%, 0% 100%, 7% 50%, 0% 0%);
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

        .select2-container {
            z-index: 9999 !important;
            margin-bottom: 1rem !important;
            /* Menjamin dropdown tampil di atas modal */
        }

        .swal2-popup {
            z-index: 1050 !important;
            /* Pastikan popup Swal berada di bawah dropdown */
        }

        .swal2-popup .select2-selection {
            margin-bottom: 1rem !important;
            /* Menambah jarak di dalam dropdown */
        }

        .welcome-text {
            font-size: 28px;
            font-weight: bold;
            opacity: 0;
            animation: tampilMasuk 1s ease-in-out forwards;
        }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(45deg, #003366, #0056b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }


        /* Animasi wave emoji */
        .wave {
            display: inline-block;
            animation: waveAnim 1s infinite;
        }

        /* Animasi fade-in */
        .fade-in {
            opacity: 0;
            animation: tampilMasuk 1.5s ease-in-out forwards 0.5s;
        }

        @keyframes tampilMasuk {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes waveAnim {

            0%,
            100% {
                transform: rotate(0);
            }

            50% {
                transform: rotate(15deg);
            }
        }
    </style>
    <div class="py-1">
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
            <div class="col-md-12 mb-4 text-center">
                <h3 class="welcome-text">
                    <span class="wave">👋</span> Selamat Datang,
                    <b class="text-gradient">{{ auth()->user()->name }}</b>
                </h3>
                <p class="text-muted fade-in">Kami senang melihat Anda kembali.</p>
            </div>
            <div class="container-fluid">

                {{-- Voucher --}}
                @if ($packages->isNotEmpty())
                    <h3 class="text-center font-weight-bold my-3 w-100">
                        Daftar <span class="custom-shape bg-gradient-lightblue">Voucher</span>
                    </h3>
                    <div class="row justify-content-center">
                        @foreach ($packages as $package)
                            <div class="col-md-5  col-12 mx-1 my-3"> {{-- Responsif di layar kecil --}}
                                <div class="card h-100 shadow-lg border-0 rounded-4">
                                    <div class="card-header bg-gradient-lightblue text-center rounded-top-4">
                                        <h5 class="font-weight-bold text-white m-0">{{ $package->name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row justify-content-center px-2">
                                            @foreach ($package->voucher as $voucher)
                                                <div class="col-md-6 mb-4">
                                                    <div class="card h-100 border-0 shadow-md rounded-3 position-relative ">
                                                        <div
                                                            class="card-header text-white {{ isset($voucher->discount) ? 'bg-success' : 'bg-warning' }}">
                                                            <span style="font-size: 1rem; font-weight: bold;">
                                                                <i class="fas fa-tags mr-2"></i>{{ $voucher->name }}
                                                            </span>
                                                        </div>
                                                        <div class="card-body text-center px-2">
                                                            @if (isset($voucher->discount))
                                                                <span class="badge bg-success mb-2"
                                                                    style="font-size: 1rem">Diskon
                                                                    {{ $voucher->discount }}<sup>%</sup></span>
                                                            @endif
                                                            @if (isset($voucher->fixed_price))
                                                                <span class="badge bg-warning text-dark mb-2"
                                                                    style="font-size: 1rem">
                                                                    Fixed Price
                                                                    {{ 'Rp. ' . number_format($voucher->fixed_price, 0, ',', '.') }}
                                                                </span>
                                                            @endif
                                                            @if (isset($voucher->description))
                                                                <span class="text-gray my-3" style="opacity: 0.8;">
                                                                    {!! $voucher->description !!}
                                                                </span>
                                                            @endif
                                                            <h4 class="mt-2 text-primary">
                                                                {{ 'Rp. ' . number_format($voucher->voucher_price, 0, ',', '.') }}
                                                            </h4>
                                                        </div>
                                                        <div class="card-footer bg-white text-center">
                                                            <button
                                                                onclick="checkOutVoucher({{ $voucher->id }}, '{{ $voucher->name }}', {{ $voucher->voucher_price }})"
                                                                class="btn btn-outline-primary w-100">
                                                                Checkout
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <img src="{{ asset('img/breadcrumb-line.png') }}" class="img-fluid">
                        </div>
                    </div>
                @endif




                {{-- Paket --}}
                @if ($type_package->isNotEmpty())
                    <h3 class="text-center font-weight-bold my-3 w-100">
                        Daftar <span class="custom-shape bg-gradient-lightblue">Paket</span>
                    </h3>

                    <div class="row justify-content-center">
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
                                                <p class="text-center text-muted">{{ $child->description ?? '' }}</p>
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
                    <h3 class="text-center font-weight-bold my-3">Belum Ada Paket</h3>
                @endif

            </div>

        </section>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
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
            });
        </script>
    @endpush
@endsection
