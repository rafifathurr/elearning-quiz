<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;"><strong>Laporan Order Paket</strong></th>
        </tr>
        <tr>
            <th colspan="2"><strong>Bulan: {{ $bulan }}</strong></th>
        </tr>
        <tr>
            <th colspan="2"><strong>Tahun: {{ $tahun }}</strong></th>
        </tr>
        <tr>
            <th>Paket</th>
            <th>Jumlah Order</th>
            <th>Jumlah Paket Dibayar</th>
            <th>Jumlah Kelas Dibuka</th>
            <th>Jumlah Kelas Berjalan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row['paket'] }}</td>
                <td>{{ $row['jumlah_order'] }}</td>
                <td>{{ $row['jumlah_paket_dibayar'] }}</td>
                <td>{{ $row['jumlah_kelas_dibuka'] }}</td>
                <td>{{ $row['jumlah_kelas_berjalan'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
