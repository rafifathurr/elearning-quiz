@extends('layouts.section')
@section('content')
    <style>
        .order .small-box {
            border-radius: 0.7rem;
        }


        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9) !important;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            text-align: center;
            z-index: 10;
            /* Pastikan overlay ada di atas */
        }

        .revenue-box {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            z-index: 5;
        }

        .revenue-box:hover .overlay {
            opacity: 1;
        }

        .revenue-box .inner {
            background-color: transparent !important;
        }
    </style>
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="order">
                    <div class="row justify-content-center" style="margin-top: 1.2rem; margin-bottom: 0.8rem">
                        <!-- Total Pesanan -->
                        <div class="col-lg-3 col-6 my-1">
                            <div class="small-box bg-gradient-cyan py-2 h-100 align-content-center ">
                                <div class="inner">
                                    <h3>{{ $all_order }}</h3>
                                    <p><b>Total Pesanan</b></p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-clipboard"></i> <!-- Ikon daftar pesanan -->
                                </div>
                            </div>
                        </div>

                        <!-- Pesanan Diambil -->
                        <div class="col-lg-3 col-6 my-1">
                            <div class="small-box bg-gradient-orange text-white py-2 h-100 align-content-center">
                                <div class="inner">
                                    <h3>{{ $check_out }}</h3>
                                    <p><b>Pesanan Diambil</b></p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-cart"></i> <!-- Ikon keranjang -->
                                </div>
                            </div>
                        </div>

                        <!-- Pesanan Belum Dibayar -->
                        <div class="col-lg-3 col-6 my-1">
                            <div class="small-box bg-gradient-danger py-2 h-100 align-content-center">
                                <div class="inner">
                                    <h3>{{ $not_payment }}<sup style="font-size: 15px"> +{{ $order_reject }} ditolak</sup>
                                    </h3>
                                    <p><b>Pesanan Belum Dibayar</b></p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-card"></i> <!-- Ikon kartu pembayaran -->
                                </div>
                            </div>
                        </div>
                        <!-- Pesanan Berhasil -->
                        <div class="col-lg-3 col-6 my-1">
                            <div
                                class="small-box bg-gradient-success py-2 h-100 align-content-center position-relative revenue-box">
                                <div class="inner">
                                    <h3>{{ $success_order }}</h3>
                                    <p><b>Pesanan Berhasil</b></p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-checkmark-circled"></i>
                                </div>

                                <!-- Overlay total pendapatan (muncul saat hover) -->
                                <div class="overlay">
                                    <p><b>Total Pendapatan</b></p>
                                    <h4>Rp {{ number_format($total_revenue, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayran -->
                        {{-- <div class="col-lg-3 col-6 my-1">
                            <div class="small-box py-2 h-100 align-content-center " style="background-color: #1976D2;">
                                <div style="height: 120px; width: 100%;">
                                    <canvas id="paymentChart"></canvas>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card  card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold">
                                    Daftar Order
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 my-1">
                                        <label for="statusFilter">Filter Status Order:</label>
                                        <select id="statusFilter" name="statusFilter" class="form-control">
                                            <option value="">-- Semua Status --</option>
                                            <option value="101">Lunas</option>
                                            <option value="102">Belum Lunas</option>
                                            <option value="10">Menunggu Konfirmasi</option>
                                            <option value="2">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <input type="hidden" id="url_dt" value="{{ $datatable_route }}">
                                    <table class="table table-bordered table-hover w-100 datatable" id="dt-order">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Order ID</th>
                                                <th>Tanggal Order</th>
                                                <th>Nama Pengguna</th>
                                                <th>Total Harga</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Status Pesanan</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    @push('javascript-bottom')
        @include('js.order.script')
        <script>
            dataTableAdmin();
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <!-- Load ChartDataLabels Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('paymentChart').getContext('2d');
                Chart.register(ChartDataLabels);

                function getLegendPosition() {
                    return window.innerWidth < 768 ? 'bottom' : 'left'; // Ubah posisi legend
                }

                var paymentChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Transfer', 'BRIVA'],
                        datasets: [{
                            data: [{{ $payment_method_count['transfer'] ?? 0 }},
                                {{ $payment_method_count['briva'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(255, 206, 86, 0.8)', // Kuning terang
                                'rgba(255, 99, 132, 0.8)' // Merah terang
                            ],
                            borderColor: [
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: getLegendPosition(),
                                labels: {
                                    color: 'white',
                                    font: {
                                        size: window.innerWidth < 768 ? 12 : 14
                                    }
                                }
                            },
                            datalabels: {
                                color: 'white',
                                font: {
                                    size: 14
                                },
                                anchor: 'center',
                                align: 'center',
                                formatter: (value, context) => {
                                    const dataset = context.chart.data.datasets[0];
                                    const total = dataset.data.reduce((acc, curr) => acc + curr, 0);
                                    const percentage = ((value / total) * 100).toFixed(
                                        1); // Hitung persentase
                                    return `${percentage}%`; // Tampilkan persentase di dalam chart
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Update posisi legend saat layar di-resize
                window.addEventListener('resize', function() {
                    paymentChart.options.plugins.legend.position = getLegendPosition();
                    paymentChart.options.plugins.legend.labels.font.size = window.innerWidth < 768 ? 12 : 14;
                    paymentChart.update();
                });
            });
        </script>
    @endpush
@endsection
