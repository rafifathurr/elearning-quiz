    <style>
        /* Navbar Default */
        #navbar {
            transition: all 0.4s ease;
            background-color: transparent;
            /* Transparan saat di posisi atas */
            z-index: 1030;
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



        /* MOBILE */
        @media (max-width: 768px) {
            .nav-mobile-adjust {
                margin-left: 1.5rem !important;
                margin-right: 0 !important;
            }

            #navbar.dropdown-open {
                transform: none !important;
                top: 0 !important;
            }

            .dropdown-menu {
                position: absolute !important;
                transform: none !important;
                top: 100% !important;
                right: 0 !important;
                margin-top: .5rem;
            }
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link font-weight-bold"
                            href="{{ request()->routeIs('landingPage') ? '#home' : route('landingPage') . '#home' }}">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link font-weight-bold"
                            href="{{ request()->routeIs('landingPage') ? '#contact' : route('landingPage') . '#contact' }}">Kontak</a>
                    </li>
                    <li class="nav-item"><a class="nav-link font-weight-bold"
                            href="{{ request()->routeIs('landingPage') ? '#package' : route('landingPage') . '#package' }}">Paket</a>
                    </li>
                    <li class="nav-item"><a class="nav-link font-weight-bold"
                            href="{{ route('testimoni') }}">Testimoni</a>
                    </li>
                    @if (request()->routeIs('landingPage'))
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" href="#" data-toggle="modal"
                                data-target="#videoModal">
                                E-Flyer
                            </a>
                        </li>
                    @endif

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

    @push('javascript-bottom')
        @include('js.order.script')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
