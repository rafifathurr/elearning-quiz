<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        body {
            padding-inline: 80px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header th {
            text-align: center;
            padding: 10px;
            border-bottom: 2px solid #000;
            /* Border bawah untuk header */
            display: flex;
            align-items: center;
            /* Vertikal center */
            justify-content: center;
            /* Horizontal center */
            font-weight: bold;
            font-size: 2rem;
        }

        .header th img {
            max-width: 80px;
            /* Atur lebar gambar jika diperlukan */
            margin-right: 10px;
            /* Menambahkan jarak antara gambar dan teks */
        }

        .main h1 {
            text-align: center;
        }

        .main table {
            width: 80%;
            border-collapse: collapse;
            text-align: center;
            page-break-inside: auto;
        }

        .main th,
        .main td {
            padding: 20px;
            text-align: left;
            border-collapse: collapse;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <th>
                    <img src="../public/img/bclogo.png" alt="" />BRATA
                    CERDAS
                </th>
            </tr>
        </table>
        <div class="main">
            <h1>{{ $resultData->quiz->name }}</h1>

            @if ($resultData->quiz->type_aspect != 'kecermatan')
                <table>
                    <tr>
                        <td style="background-color: #d9d9d9">Waktu Mulai</td>
                        <td>
                            {{ \Carbon\Carbon::parse($resultData->start_time)->translatedFormat('l, d F Y, H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9d9d9">Waktu Selesai</td>
                        <td>
                            {{ \Carbon\Carbon::parse($resultData->finish_time)->translatedFormat('l, d F Y, H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9d9d9">
                            Durasi Pengerjaan
                        </td>
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
                    <tr>
                        <td style="background-color: #d9d9d9">Total Skor</td>
                        <td>
                            {{ $resultData->total_score }}/{{ count($resultData->details) }}
                        </td>
                    </tr>
                </table>
                <table style=" margin-top: 30px;">
                    <thead style="background-color: #d9d9d9;">
                        <tr>
                            <td>Nama Aspek</td>
                            <td>Persentase</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questionsPerAspect as $aspect)
                            <tr>
                                <td>{{ $aspect['aspect_name'] }}</td>
                                <td>{{ $aspect['percentage'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h4>Kesimpulan:</h4>
                @foreach ($questionsPerAspect as $aspect)
                    @if ($aspect['percentage'] >= 90)
                        <p style="color: green">
                            Anda sudah <strong>baik</strong> dalam
                            aspek <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 90 && $aspect['percentage'] >= 80)
                        <p style="color: green">
                            Anda <strong>cukup baik</strong> dalam
                            aspek <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 80 && $aspect['percentage'] >= 70)
                        <p style="color: green">
                            Anda <strong>cukup</strong>
                            dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 70 && $aspect['percentage'] >= 50)
                        <p style="color: orange">
                            Anda masih
                            <strong>kurang</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @elseif ($aspect['percentage'] < 50)
                        <p style="color: red">
                            Anda masih
                            <strong>kurang sekali</strong> dalam aspek
                            <strong>{{ $aspect['aspect_name'] }}</strong>.
                        </p>
                    @endif
                @endforeach
            @else
                <table>
                    <tr>
                        <td style="background-color: #d9d9d9">Waktu Mulai</td>
                        <td>
                            {{ \Carbon\Carbon::parse($resultData->start_time)->translatedFormat('l, d F Y, H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9d9d9">Waktu Selesai</td>
                        <td>
                            {{ \Carbon\Carbon::parse($resultData->finish_time)->translatedFormat('l, d F Y, H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9d9d9">Durasi Pengerjaan</td>
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
                    <tr>
                        <td style="background-color: #d9d9d9">Kecepatan</td>
                        <td>{{ $speed }}</td> <!-- Menampilkan Kecepatan -->
                    </tr>
                    <tr>
                        <td style="background-color: #d9d9d9">Ketelitian</td>
                        <td>{{ $accuracyLabel }}</td> <!-- Menampilkan Ketelitian -->
                    </tr>
                </table>

            @endif
        </div>
    </div>

</body>

</html>
