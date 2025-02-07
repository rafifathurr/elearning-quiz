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

    .meeting-info {
        font-size: 1rem;
        font-weight: bold;
        color: #ffc107;
        /* Warna kuning agar kontras */
    }

    .price-badge {
        background: white;
        color: black;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 8px;
        font-size: 0.9rem;
    }
</style>
<ul class="text-center list-unstyled p-2 m-0">
    @forelse ($packages as $package)
        <li class="mb-3">
            @if (Auth::check())
                <button onclick="checkOut({{ $package->id }}, '{{ $package->name }}')"
                    class="stylish-button w-100 shadow-sm">
                    <span><i class="fas fa-box"></i> {{ $package->name }}</span>

                    @if (isset($package->class) && $package->class > 0)
                        <span class="meeting-info">{{ $package->class }}x Pertemuan</span>
                    @endif

                    <span class="price-badge">
                        {{ $package->price > 0 ? 'Rp. ' . number_format($package->price, 0, ',', '.') : 'Gratis' }}
                    </span>
                </button>
            @else
                <a href="{{ route('login') }}" class="stylish-button w-full shadow-sm">
                    <span><i class="fas fa-box"></i> {{ $package->name }}</span>

                    @if (isset($package->class) && $package->class > 0)
                        <span class="meeting-info">{{ $package->class }}x Pertemuan</span>
                    @endif

                    <span class="price-badge">
                        {{ $package->price > 0 ? 'Rp. ' . number_format($package->price, 0, ',', '.') : 'Gratis' }}
                    </span>
                </a>
            @endif
        </li>
    @empty
        <p class="font-weight-bolder text-danger">-- Belum Ada Paket --</p>
    @endforelse
</ul>
