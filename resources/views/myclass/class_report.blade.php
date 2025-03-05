<table>
    <thead>
        <tr>
            <th colspan="3"> <strong>Laporan Kegiatan Kelas Paket-{{ $classes->first()->package->name }}</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($classes as $class)
            <tr>
                <td colspan="3"><strong>Kelas:</strong> {{ $class->name }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Pembimbing:</strong>
                    {{ $class->classCounselor->map->counselor->pluck('name')->join(', ') }}
                </td>
            </tr>
            @foreach ($class->classAttendances->groupBy('created_at') as $date => $attendances)
                <tr>
                    <td colspan="3"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td><strong>Telepon</strong></td>
                    <td><strong>Email</strong></td>
                </tr>
                @foreach ($attendances as $attendance)
                    @if ($attendance->attendance == 1)
                        <tr>
                            <td>{{ $attendance->orderPackage->order->user->name }}</td>
                            <td>{{ $attendance->orderPackage->order->user->phone }}</td>
                            <td>{{ $attendance->orderPackage->order->user->email }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
