<ul class="text-center list-unstyled p-2 m-0">
    @forelse ($packages as $package)
        <li class="mb-3">
            @if (Auth::check())
                <button onclick="checkOut({{ $package->id }}, '{{ $package->name }}')"
                    class="btn btn-primary w-100 rounded-lg shadow-sm" style="font-size: 1.1rem;">
                    {{ $package->name }} <br>
                    <span class="bg-white p-1 font-weight-bold rounded" style="font-size: 0.9rem;">
                        {{ $package->price > 0 ? 'Rp. ' . number_format($package->price, 0, ',', '.') : 'Gratis' }}
                    </span>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary w-full d-block font-weight-bold p-2 rounded-lg"
                    style="font-size: 1.2rem;">{{ $package->name }}
                    <br>
                    <span class="bg-white p-1 font-weight-bold rounded" style="font-size: 0.9rem;">
                        {{ $package->price > 0 ? 'Rp. ' . number_format($package->price, 0, ',', '.') : 'Gratis' }}
                    </span>
                </a>
            @endif
        </li>
    @empty
        <p class="font-weight-bolder text-danger">-- Belum Ada Paket --</p>
    @endforelse
</ul>
