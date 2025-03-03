@extends('layouts.section')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-lightblue text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-gift mr-2"></i>Detail Paket - {{ $package->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted">Nama Paket</th>
                                        <td>{{ $package->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Kategori Paket</th>
                                        <td>{{ $package->typePackage->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Harga Paket</th>
                                        <td>{{ 'Rp.' . number_format($package->price, 0, ',', '.') }}</td>
                                    </tr>

                                    @if (!is_null($package->class) && $package->class > 0)
                                        <tr>
                                            <th class="text-muted">Jumlah Pertemuan</th>
                                            <td>{{ $package->class . ' Kali Pertemuan' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Maksimal Peserta</th>
                                            <td>{{ $package->max_member . ' Peserta' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Jadwal Kelas</th>
                                            <td>
                                                @forelse ($package->packageDate as $date)
                                                    <span class="badge badge-primary m-1 py-2 px-3"
                                                        style="font-size: 0.85rem;">
                                                        <i class="fas fa-calendar-alt m-1"></i>{{ $date->dateClass->name }}
                                                    </span>
                                                @empty
                                                    -
                                                @endforelse
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th class="text-muted">Jenis Test</th>
                                        <td>
                                            @forelse ($package->packageTest as $test)
                                                <span class="badge badge-primary m-1 py-2 px-3" style="font-size: 0.85rem;">
                                                    <i class="fas fa-calendar-check m-1"></i>{{ $test->quiz->name }}
                                                </span>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <hr>
                            <h4 class="text-muted font-weight-bold">Informasi Paket</h4>
                            <div class="row justify-content-center" style="margin-top: 1.2rem;margin-bottom: 0.8rem">

                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-success py-2">
                                        <div class="inner">
                                            <h3>{{ $orderPackage }}
                                            </h3>
                                            <p><b>Paket diambil</b></p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-archive"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info py-2">
                                        <div class="inner">
                                            <h3>
                                                {{ $packageSold }}
                                            </h3>
                                            <p><b>Paket terbayar</b></p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-cash"></i>
                                        </div>
                                    </div>
                                </div>

                                @if (!is_null($package->class) && $package->class > 0)
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-maroon py-2">
                                            <div class="inner">
                                                <h3>{{ $classOpen }}</h3>
                                                <p><b>Kelas dibuka</b></p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-ios-book"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <a href="{{ route('master.package.index') }}" class="btn btn-danger">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
