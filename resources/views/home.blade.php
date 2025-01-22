@extends('layouts.section')
@section('content')
    <style>
        .list-unstyled {
            list-style-type: none;
            padding-left: 0;
        }

        .list-unstyled .package-item {
            position: relative;
            padding-left: 30px;
            /* Space for the icon */
        }

        .list-unstyled .package-item::before {
            content: "\f00c";
            /* FontAwesome checkmark icon */
            font-family: "Font Awesome 5 Free";
            /* Font Awesome family */
            font-weight: 900;
            /* Required for solid icons */
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: green;
            /* Icon color */
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

        .equal-height {
            min-height: 350px;
            /* Sesuaikan nilai sesuai kebutuhan */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
    <div class=" py-1">
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h3>Selamat Datang, <b>{{ auth()->user()->name }}</b> !</h3>
                    </div>

                    <!-- Daftar Test -->
                    <div class="col-md-6 " style="background-color: #D3E9F6">

                        <h4 class="text-center my-2 ml-2 font-weight-bold">Daftar Test</h4>
                        <div class="row mx-2">
                            @foreach ($tests as $test)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 equal-height">
                                        <div class="card-header">
                                            <h6 class="font-weight-bolder ">{{ $test->name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                @if ($test->price > 0)
                                                    <p class="fs-6"><i class="fa fa-tags text-danger"></i>
                                                        {{ 'Rp. ' . number_format($test->price, 0, ',', '.') }}</p>
                                                @else
                                                    <p class="mb-3 text-success fs-6"><i class="fa fa-gift "></i>
                                                        Gratis</p>
                                                @endif
                                            </div>
                                            <ul class="list-unstyled">
                                                @foreach ($test->packageTest as $package)
                                                    <?php
                                                    $colorMapping = [
                                                        'kecerdasan' => 'badge-info',
                                                        'kepribadian' => 'badge-secondary',
                                                        'kecermatan' => 'badge-warning',
                                                    ];
                                                    
                                                    $colorVar = $colorMapping[$package->quiz->type_aspect] ?? '';
                                                    ?>
                                                    <li class="package-item font-weight-normal my-1">
                                                        {{ $package->quiz->name }}
                                                        <span
                                                            class="badge {{ $colorVar }}  font-weight-bolder ">{{ $package->quiz->type_aspect }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-end">
                                                @hasrole('user')
                                                    <button onclick="checkOut({{ $test->id }}, '{{ $test->name }}')"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-shopping-cart"></i> Checkout</button>
                                                @endhasrole
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <!-- Daftar Kelas -->
                    <div class="col-md-6" style="background-color: #D0F8D0">
                        <h4 class="text-center my-2">Daftar Kelas</h4>
                        <div class="row mx-2">
                            @foreach ($classes as $class)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 equal-height">
                                        <div class="card-header">
                                            <h6>{{ $class->name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                @if ($class->price > 0)
                                                    <p class="fs-6"><i class="fa fa-tags text-danger"></i>
                                                        {{ 'Rp. ' . number_format($class->price, 0, ',', '.') }}</p>
                                                @else
                                                    <p class="mb-3 text-success fs-6"><i class="fa fa-gift "></i>
                                                        Gratis</p>
                                                @endif
                                                <p><i class="fa fa-key text-warning"></i> {{ $class->class }}x Pertemuan
                                                </p>
                                            </div>
                                            <ul class="list-unstyled">
                                                @foreach ($class->packageTest as $package)
                                                    <?php
                                                    $colorMapping = [
                                                        'kecerdasan' => 'badge-info',
                                                        'kepribadian' => 'badge-secondary',
                                                        'kecermatan' => 'badge-warning',
                                                    ];
                                                    
                                                    $colorVar = $colorMapping[$package->quiz->type_aspect] ?? '';
                                                    ?>
                                                    <li class="package-item font-weight-normal my-1">
                                                        {{ $package->quiz->name }}
                                                        <span
                                                            class="badge {{ $colorVar }}  font-weight-bolder ">{{ $package->quiz->type_aspect }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-end">
                                                @hasrole('user')
                                                    <button onclick="checkOut({{ $class->id }}, '{{ $class->name }}')"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-shopping-cart"></i> Checkout</button>
                                                @endhasrole
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('javascript-bottom')
        @include('js.order.script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.equal-height');
                let maxHeight = 0;

                // Cari tinggi maksimum
                cards.forEach(card => {
                    const cardHeight = card.offsetHeight;
                    if (cardHeight > maxHeight) {
                        maxHeight = cardHeight;
                    }
                });

                // Terapkan tinggi maksimum ke semua card
                cards.forEach(card => {
                    card.style.height = maxHeight + 'px';
                });
            });
        </script>
    @endpush
@endsection
