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

    .package-description {
        font-size: 0.9rem;
        color: #f8f9fa;
        /* Warna abu-abu terang */
        text-align: center;
        display: block;
        padding: 5px 10px;
        opacity: 0.8;
    }

    .package-meta {

        align-self: flex-start;
        font-size: 0.85rem;
        font-weight: bold;
        color: #fff;
        opacity: 0.9;
    }
</style>
<?php
if (Auth::check()) {
    if (auth()->user()->hasRole('counselor')) {
        $onClick = "checkOutCounselor({$package->id}, '{$package->name}')";
    } else {
        $onClick = "checkOut({$package->id}, '{$package->name}')";
    }
} else {
    $onClick = null;
}
?>

@if ($onClick)
    <button onclick="{{ $onClick }}" class="stylish-button w-100 shadow-sm">
        <span style="font-size: 1.1rem"><i class="fas fa-box"></i> {{ $package->name }}</span>

        {{-- Info Pertemuan & Peserta --}}
        @if (!empty($package->class) || !empty($package->max_member))
            <div class="d-flex justify-content-center align-items-center gap-2">
                @if (!empty($package->class))
                    <span class="meeting-info">
                        <i class="fas fa-calendar-alt"></i> {{ $package->class }}x Pertemuan
                    </span>
                @endif

                @if (!empty($package->class) && !empty($package->max_member))
                    <span class="divider">|</span>
                @endif

                @if (!empty($package->max_member))
                    <span class="member-info">
                        <i class="fas fa-users mr-1"></i> Max: {{ $package->max_member }} Peserta
                    </span>
                @endif
            </div>
        @endif

        {{-- Deskripsi --}}
        @if (!empty($package->description))
            <span class="package-description">
                {!! nl2br(e($package->description)) !!}
            </span>
        @endif

        {{-- Harga --}}
        <span class="price-badge">
            @if ($package->price > 0)
                Rp. {{ number_format($package->price, 0, ',', '.') }}
                <span class="per-peserta">/ Peserta</span>
            @else
                Gratis
            @endif
        </span>

        {{-- Meta --}}
        @if ($showMeta)
            <span class="package-meta">
                {{ $package->jenis }} | {{ $package->sesi }}
            </span>
        @endif
    </button>
@else
    <a href="{{ route('login') }}" class="stylish-button w-100 shadow-sm">
        <span style="font-size: 1.1rem"><i class="fas fa-box"></i> {{ $package->name }}</span>

        {{-- Info Pertemuan & Peserta --}}
        @if (!empty($package->class) || !empty($package->max_member))
            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                @if (!empty($package->class))
                    <span class="meeting-info">
                        <i class="fas fa-calendar-alt"></i> {{ $package->class }}x Pertemuan
                    </span>
                @endif

                @if (!empty($package->class) && !empty($package->max_member))
                    <span class="divider">|</span>
                @endif

                @if (!empty($package->max_member))
                    <span class="member-info">
                        <i class="fas fa-users mr-1"></i> Max: {{ $package->max_member }} Peserta
                    </span>
                @endif
            </div>
        @endif

        {{-- Deskripsi --}}
        @if (!empty($package->description))
            <span class="package-description">
                {!! nl2br(e($package->description)) !!}
            </span>
        @endif

        {{-- Harga --}}
        <span class="price-badge mt-2">
            @if ($package->price > 0)
                Rp. {{ number_format($package->price, 0, ',', '.') }}
                <span class="per-peserta">/ Peserta</span>
            @else
                Gratis
            @endif
        </span>

        {{-- Meta --}}
        @if ($showMeta)
            <span class="package-meta">
                {{ $package->aspek }} | {{ $package->sesi }} | {{ $package->jenis }}
            </span>
        @endif
    </a>
@endif
