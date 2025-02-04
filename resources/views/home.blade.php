@extends('layouts.section')
@section('content')
    <style>
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
            <div class="col-md-12 mb-2">
                <h3>Selamat Datang, <b>{{ auth()->user()->name }}</b> !</h3>
            </div>
            <div class="container-fluid">

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
    </div>
    @push('javascript-bottom')
        @include('js.order.script')
    @endpush
@endsection
