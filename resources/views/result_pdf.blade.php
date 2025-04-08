<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hasil Evaluasi</title>
    <style>
        body {
            font-family: Georgia, serif;
            padding: 40px;
            color: #333;
            background-color: #f9f9f9;
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
            margin-bottom: 30px;
            border-bottom: 4px solid #0056b3;
        }

        .header img {
            max-width: 60px;
            vertical-align: middle;
        }

        .header h1 {
            display: inline-block;
            font-size: 2rem;
            margin-left: 12px;
            color: #0056b3;
            font-weight: bold;
        }

        .main {
            text-align: center;
        }

        .main h2 {
            font-size: 1.8rem;
            color: #003366;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .table-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        td:first-child {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .conclusion {
            margin-top: 30px;
        }

        .conclusion h3 {
            font-size: 1.5rem;
            color: #003366;
            text-align: center;
        }

        .conclusion p {
            font-size: 1rem;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            width: 60%;
            margin: 10px auto;
        }

        .good {
            background-color: #d4edda;
            color: #155724;
        }

        .average {
            background-color: #fff3cd;
            color: #856404;
        }

        .poor {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="../public/img/bclogo.png" alt="Logo" />
        <h1>BRATA CERDAS</h1>
    </div>

    <div class="main">
        <h2>{{ $resultData->quiz->name }}</h2>

        <div align="center" class="table-container">
            <table>
                <tr>
                    <td>Waktu Mulai</td>
                    <td>{{ \Carbon\Carbon::parse($resultData->start_time)->translatedFormat('l, d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td>Waktu Selesai</td>
                    <td>{{ \Carbon\Carbon::parse($resultData->finish_time)->translatedFormat('l, d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td>Durasi Pengerjaan</td>
                    <td>
                        @php
                            $startTime = \Carbon\Carbon::parse($resultData->start_time);
                            $finishTime = \Carbon\Carbon::parse($resultData->finish_time);
                            $duration = $finishTime->diff($startTime);
                        @endphp
                        {{ $duration->h != 0 ? $duration->h . ' jam' : '' }}
                        {{ $duration->i != 0 ? $duration->i . ' menit' : '' }}
                        {{ $duration->s . ' detik' }}
                    </td>
                </tr>
                @if ($resultData->quiz->type_aspect != 'kecermatan')
                    <tr>
                        <td>Skor</td>
                        <td><strong>{{ $resultData->total_score }}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td>Kecepatan</td>
                        <td>{{ $speed }}</td>
                    </tr>
                    <tr>
                        <td>Ketelitian</td>
                        <td>{{ $accuracyLabel }}</td>
                    </tr>
                @endif
            </table>
        </div>

        @if ($resultData->quiz->type_aspect != 'kecermatan')
            <div class="conclusion">
                <h3>Kesimpulan</h3>
                @foreach ($questionsPerAspect as $aspect)
                    @if ($aspect['percentage'] >= 90)
                        <p class="good">Hasil anda sudah <strong>baik</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 90 && $aspect['percentage'] >= 80)
                        <p class="good">Hasil Anda <strong>cukup baik</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 80 && $aspect['percentage'] >= 70)
                        <p class="average">Hasil Anda <strong>cukup</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 70 && $aspect['percentage'] >= 50)
                        <p class="poor">Anda masih <strong>kurang</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 50)
                        <p class="poor">Anda masih <strong>kurang sekali</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @endif
                @endforeach
                @if (
                    $questionsPerAspect->pluck('percentage')->contains(function ($percentage) {
                        return $percentage < 70;
                    }))
                    <p><strong>Anda dapat mengikuti tes kembali ataupun mengikuti sesi konseling
                            online dan
                            offline.</strong></p>
                @endif
            </div>
        @else
            @if (file_exists($chartPath))
                <div style="text-align: center;">
                    <img src="{{ $chartPath }}" style="width: 100%; max-width: 600px;" alt="Chart">
                </div>
            @endif
        @endif
    </div>


</body>

</html>
