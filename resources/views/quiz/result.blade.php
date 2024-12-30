@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 text-center">
                                        <h5 class="font-weight-bold">{{ $result->quiz->name }}</h5>
                                        <h3 class="font-weight-medium">Total Point: {{ $result->total_score }} Point</h3>

                                        <h6>Waktu Submit:
                                            {{ \Carbon\Carbon::parse($result->finish_time)->translatedFormat('H:i d F Y') }}
                                        </h6>
                                    </div>
                                </div>

                                @if ($result->quiz->type_aspect != 'kecermatan')
                                    <div class="row mt-3">
                                        <div class="col-md-6 mx-auto">
                                            @foreach ($questionsPerAspect as $aspect)
                                                @if ($aspect['percentage'] >= 90)
                                                    <p class="text-success">Hasil Anda sudah <strong>baik</strong> dalam
                                                        aspek
                                                        <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                    </p>
                                                @elseif ($aspect['percentage'] < 90 && $aspect['percentage'] >= 80)
                                                    <p class="text-success">Hasil Anda <strong>cukup baik</strong> dalam
                                                        aspek
                                                        <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                    </p>
                                                @elseif ($aspect['percentage'] < 80 && $aspect['percentage'] >= 70)
                                                    <p class="text-success">Hasil Anda <strong>cukup</strong> dalam aspek
                                                        <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                    </p>
                                                @elseif ($aspect['percentage'] < 70 && $aspect['percentage'] >= 50)
                                                    <p style="color: orange">
                                                        Anda masih <strong>kurang</strong> dalam aspek
                                                        <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                    </p>
                                                @elseif ($aspect['percentage'] < 50)
                                                    <p class="text-danger">
                                                        Anda masih <strong>kurang sekali</strong> dalam aspek
                                                        <strong>{{ $aspect['aspect_name'] }}</strong>.
                                                    </p>
                                                @endif
                                            @endforeach

                                            {{-- Menampilkan narasi tes dan konseling jika ada aspek dengan persentase kurang dari 70 --}}
                                            @if (
                                                $questionsPerAspect->pluck('percentage')->contains(function ($percentage) {
                                                    return $percentage < 70;
                                                }))
                                                <p><strong>Anda dapat mengikuti tes kembali ataupun mengikuti sesi konseling
                                                        online dan
                                                        offline.</strong></p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row mt-3">
                                        <div class="col-md-10 mx-auto">
                                            <canvas id="combinationChart"></canvas>
                                        </div>
                                    </div>
                                @endif

                                <div class="row justify-content-center">
                                    <div class="col-md-6 text-center">
                                        <div class="d-flex pt-3 justify-content-center">
                                            <div class="mx-2">
                                                <a href="{{ url('/') }}" class="btn btn-lg btn-danger">
                                                    <i class="fas fa-home mr-2"></i>Halaman Utama
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('javascript-bottom')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.getElementById('mulaiKembali').addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pengalihan default

                // Hapus waktu dari localStorage ketika tombol "Mulai" diklik
                localStorage.removeItem('remainingTime');

                // Setelah menghapus, pindahkan halaman secara manual
                window.location.href = this.href;
            });
        </script>

        @if ($result->quiz->type_aspect == 'kecermatan')
            <script>
                // Ambil data dari PHP dan kirim ke JavaScript
                const accuracyData = @json($accuracyData);
                const formattedCombinations = @json($formattedCombinations);

                // Buat array untuk sumbu X dan data untuk grafik
                const labels = formattedCombinations.map(num => 'Kecermatan' + num);
                const totalQuestions = labels.map(label => accuracyData[label]?.total_questions || 0);
                const correctQuestions = labels.map(label => accuracyData[label]?.correct_questions || 0);

                const ctx = document.getElementById('combinationChart').getContext('2d');
                const combinationChart = new Chart(ctx, {
                    type: 'line', // Tipe chart
                    data: {
                        labels: labels, // Nama kecermatan untuk sumbu X
                        datasets: [{
                                label: 'Jumlah Jawaban',
                                data: totalQuestions, // Data total pertanyaan
                                borderColor: 'rgba(75, 192, 192, 1)', // Warna garis
                                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna latar belakang
                            },
                            {
                                label: 'Jawaban Benar',
                                data: correctQuestions, // Data jawaban benar
                                borderColor: 'rgba(255, 99, 132, 1)', // Warna garis
                                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna latar belakang
                            },
                        ],
                    },
                    options: {
                        responsive: true, // Responsive design
                        plugins: {
                            legend: {
                                position: 'top', // Posisi legenda
                            },
                            title: {
                                display: true,
                                text: 'Hasil Jawaban Per Kecermatan', // Judul Chart
                            },
                        },
                        scales: {
                            y: {
                                title: {
                                    display: true,
                                    text: 'Jumlah', // Label sumbu Y
                                },
                                beginAtZero: true, // Mulai dari 0
                                ticks: {
                                    // Tampilkan hanya bilangan bulat
                                    callback: function(value) {
                                        return Number.isInteger(value) ? value : null;
                                    },
                                    stepSize: 1, // Langkah antar nilai
                                },
                            },
                        },
                    },
                });
            </script>
        @endif
    @endpush
@endsection
