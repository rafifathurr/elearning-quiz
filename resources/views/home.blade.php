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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h3>Selamat Datang, <b>{{ auth()->user()->name }}</b> !</h3>
                    </div>
                    @if ($type_package->isNotEmpty())
                        <h3 class="text-center font-weight-bold my-3 w-100">
                            Daftar <span class="custom-shape bg-gradient-lightblue">Paket</span>
                        </h3>

                        <div class="row justify-content-center">
                            @foreach ($type_package as $type)
                                <div class="col-md-5 mx-1 my-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-gradient-lightblue">
                                            <h5 class="font-weight-bold">{{ $type->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-center">{{ $type->description }}</p>

                                            <div class="border rounded-lg p-2 mx-2">
                                                <h4 class="text-center font-weight-bold text-blue mb-2">Daftar Paket</h4>
                                                <ul class="text-center list-unstyled p-2 m-0">
                                                    @foreach ($type->package as $package)
                                                        <li class="my-3">
                                                            <button
                                                                onclick="checkOut({{ $package->id }}, '{{ $package->name }}')"
                                                                class="btn btn-primary w-100 rounded-lg"
                                                                style="font-size: 1.2rem;">{{ $package->name }}</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h3 class="text-center font-weight-bold my-3">
                            Belum Ada Paket
                        </h3>
                    @endif
                </div>
            </div>
        </section>
    </div>
    @push('javascript-bottom')
        @include('js.order.script')
    @endpush
@endsection
