<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 50px;
            vertical-align: middle;
        }

        .header h1 {
            display: inline-block;
            font-size: 1.8rem;
            margin-left: 10px;
            color: #007bff;
        }

        .main {
            text-align: center;
        }

        .main h2 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .table-container {
            display: flex;
            justify-content: center;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .conclusion p {
            font-size: 1.1rem;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .conclusion .good {
            background-color: #d4edda;
            color: #155724;
        }

        .conclusion .average {
            background-color: #fff3cd;
            color: #856404;
        }

        .conclusion .poor {
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

        <div class="table-container">
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
            <h3 style="margin-top: 20px;">Kesimpulan:</h3>
            <div class="conclusion">
                @foreach ($questionsPerAspect as $aspect)
                    @if ($aspect['percentage'] >= 90)
                        <p class="good">Anda sudah <strong>baik</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.</p>
                    @elseif ($aspect['percentage'] < 90 && $aspect['percentage'] >= 80)
                        <p class="good">Anda <strong>cukup baik</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.</p>
                    @elseif ($aspect['percentage'] < 80 && $aspect['percentage'] >= 70)
                        <p class="average">Anda <strong>cukup</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.</p>
                    @elseif ($aspect['percentage'] < 70 && $aspect['percentage'] >= 50)
                        <p class="poor">Anda masih <strong>kurang</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.</p>
                    @elseif ($aspect['percentage'] < 50)
                        <p class="poor">Anda masih <strong>kurang sekali</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.</p>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</body>


</html>
