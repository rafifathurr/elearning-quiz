<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .class-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #f9f9f9;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .class-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .attendance-list {
            margin-top: 10px;
        }

        .attendance-item {
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
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
            <h2>Laporan Member</h2>
            <p><strong>Nama:</strong> {{ $orderPackage->order->user->name }}</p>
            <p><strong>Email:</strong> {{ $orderPackage->order->user->email }}</p>
            <p><strong>Telepon:</strong> {{ $orderPackage->order->user->phone }}</p>
        </div>

        <h3>Kelas yang Diikuti</h3>

        @foreach ($classUsers as $classUser)
            <div class="class-card">
                <div class="class-title">
                    {{ $classUser->class->name }}
                </div>
                <p><strong>Meeting Saat Ini:</strong> {{ $classUser->class->current_meeting }}</p>

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
                                    class="attendance-status {{ $attendance->attendance == 1 ? 'present' : 'absent' }} ">
                                    ({{ $attendance->attendance == 1 ? 'Hadir' : 'Tidak Hadir' }})
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <h4>Hasil Tes</h4>
                @if ($tests->isEmpty())
                    <p>Belum ada hasil tes untuk kelas ini.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Test</th>
                                <th>Total Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $test->quiz->name }}</td>
                                    <td>{{ $test->total_score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    </div>
</body>

</html>
