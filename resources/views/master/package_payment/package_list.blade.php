<style>
    .stylish-button {
        background: linear-gradient(135deg, #007bff, #0056b3);
        /* Gradient warna biru */
        color: white !important;
        border: none;
        font-size: 1.1rem;
        font-weight: bold;
        padding: 15px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        text-decoration: none;
    }

    .stylish-button:hover {
        transform: scale(1.05);
        /* Efek hover membesar */
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        color: inherit !important white;
    }

    .meeting-info,
    .member-info {
        font-size: 1rem;
        font-weight: bold;
    }

    .meeting-info {
        color: #ffc107;
        /* Kuning */
    }

    .member-info {
        color: #28FFBF;
        /* Hijau terang agar lebih kontras */
    }



    /* Gaya pembatas */
    .divider {
        font-size: 1rem;
        font-weight: bold;
        color: white;
        /* Warna putih supaya kontras dengan button */
        opacity: 0.7;
        /* Sedikit transparan agar lebih elegan */
        margin: 0 5px;
        /* Spasi kiri dan kanan agar tidak terlalu mepet */
    }


    .price-badge {
        background: white;
        color: black;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .per-peserta {
        font-size: 0.8em;
        font-weight: bold;
        color: black;
        /* Warna menarik */
    }
</style>
<ul class="text-center list-unstyled p-2 m-0">
    @forelse ($packages as $package)
        <li class="mb-3">
            @if (Auth::check())
                <button onclick="checkOut({{ $package->id }}, '{{ $package->name }}')"
                    class="stylish-button w-100 shadow-sm">
                    <span style="font-size: 1.1rem"><i class="fas fa-box"></i> {{ $package->name }}</span>

                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                        @if (isset($package->class) && $package->class > 0)
                            <span class="meeting-info">
                                <i class="fas fa-calendar-alt"></i> {{ $package->class }}x Pertemuan
                            </span>
                        @endif

                        @if (isset($package->class) && $package->class > 0 && isset($package->max_member) && $package->max_member > 0)
                            <span class="divider">|</span> <!-- Pembatas -->
                        @endif

                        @if (isset($package->max_member) && $package->max_member > 0)
                            <span class="member-info">
                                <i class="fas fa-users mr-1"></i>Max: {{ $package->max_member }} Peserta
                            </span>
                        @endif
                    </div>

                    <span class="price-badge">
                        @if ($package->price > 0)
                            Rp. {{ number_format($package->price, 0, ',', '.') }} <span class="per-peserta">/
                                Peserta</span>
                        @else
                            Gratis
                        @endif
                    </span>

                </button>
            @else
                <a href="{{ route('login') }}" class="stylish-button w-full shadow-sm">
                    <span style="font-size: 1.1rem"><i class="fas fa-box"></i> {{ $package->name }}</span>

                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                        @if (isset($package->class) && $package->class > 0)
                            <span class="meeting-info">
                                <i class="fas fa-calendar-alt"></i> {{ $package->class }}x Pertemuan
                            </span>
                        @endif

                        @if (isset($package->class) && $package->class > 0 && isset($package->max_member) && $package->max_member > 0)
                            <span class="divider">|</span> <!-- Pembatas -->
                        @endif

                        @if (isset($package->max_member) && $package->max_member > 0)
                            <span class="member-info">
                                <i class="fas fa-users mr-1"></i>Max: {{ $package->max_member }} Peserta
                            </span>
                        @endif
                    </div>

                    <span class="price-badge">
                        @if ($package->price > 0)
                            Rp. {{ number_format($package->price, 0, ',', '.') }} <span class="per-peserta">/
                                Peserta</span>
                        @else
                            Gratis
                        @endif
                    </span>
                </a>
            @endif
        </li>
    @empty
        <p class="font-weight-bolder text-danger">-- Belum Ada Paket --</p>
    @endforelse
</ul>
