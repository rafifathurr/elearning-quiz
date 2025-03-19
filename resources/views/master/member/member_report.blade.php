<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Peserta - {{ $orderPackage->order->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header Styling */
        .header {
            text-align: center;
            padding-bottom: 15px;
            margin-bottom: 30px;
            border-bottom: 4px solid black;
        }

        .header img {
            max-width: 60px;
            vertical-align: middle;
        }

        .header h1 {
            display: inline-block;
            font-size: 2rem;
            margin-left: 12px;
            color: gold;
            font-weight: bold;
        }

        /* Member Info Styling */
        .member-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 16px;
        }

        .info-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
        }

        .info-row strong {
            min-width: 120px;
            display: inline-block;
            text-align: left;
        }

        .info-row span {
            flex-grow: 1;
        }

        /* Class Card Styling */
        .class-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .class-title {
            font-size: 18px;
            font-weight: bold;
            color: #007BFF;
        }

        .attendance-list,
        .test-results {
            margin-top: 15px;
        }

        .attendance-item {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .attendance-status {
            font-weight: bold;
        }

        .present {
            color: green;
        }

        .absent {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #007BFF;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="../public/img/bclogo.png" alt="Logo" />
            <h1>BRATA CERDAS</h1>
        </div>

        <div class="member">
            <h2>Laporan Peserta</h2>
            <div class="member-info">
                <div class="info-row">
                    <strong>Nama</strong> <span>:{{ $orderPackage->order->user->name }}</span>
                </div>
                <div class="info-row">
                    <strong>Email</strong> <span>:{{ $orderPackage->order->user->email }}</span>
                </div>
                <div class="info-row">
                    <strong>Telepon</strong> <span>:{{ $orderPackage->order->user->phone }}</span>
                </div>
            </div>
        </div>

        <h3>Kelas yang Diikuti</h3>

        @foreach ($classUsers as $classUser)
            <div class="class-card">
                <div class="class-title">{{ $classUser->class->name }}</div>
                <p><strong>Pertemuan Saat Ini:</strong>Pertemuan Ke-{{ $classUser->class->current_meeting }}</p>

                <div class="attendance-list">
                    <h4>Absensi</h4>
                    @if ($classUser->class->classAttendances->isEmpty())
                        <p>Belum ada data absensi.</p>
                    @else
                        @foreach ($classUser->class->classAttendances as $attendance)
                            <div class="attendance-item">
                                <span><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</span>
                                <span
                                    class="attendance-status {{ $attendance->attendance == 1 ? 'present' : 'absent' }}">
                                    ({{ $attendance->attendance == 1 ? 'Hadir' : 'Tidak Hadir' }})
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="test-results">
                    <h4>Hasil Tes</h4>
                    @if ($tests->isEmpty())
                        <p>Belum ada hasil tes untuk kelas ini.</p>
                    @else
                        @foreach ($tests->groupBy('orderDetail.on_meeting') as $meeting => $testsGroup)
                            <h5>{{ empty($meeting) ? 'Test Sebelum Kelas Dimulai' : 'Pertemuan Ke-' . $meeting }}</h5>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Test</th>
                                        <th>Total Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testsGroup as $test)
                                        <tr>
                                            <td>{{ $test->quiz->name ?? 'Tidak ada data' }}</td>
                                            <td>{{ $test->total_score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
