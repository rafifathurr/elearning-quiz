<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice </title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333;">
    <div
        style="max-width: 600px; background: #fff; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #007bff;">Invoice Pembayaran</h2>
        <p style="text-align: center;">Mohon Untuk Segera Melakukan Pembayaran.</p>

        <!-- Informasi Pemesan -->
        <h4 style="background: #007bff; color: #fff; padding: 10px; border-radius: 5px;">Detail Pemesan</h4>
        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px;">Nama</td>
                <td style="padding: 8px; font-weight: bold;">{{ $order->user->name }}</td>
            </tr>
            <tr style="background: #f9f9f9;">
                <td style="padding: 8px;">Email</td>
                <td style="padding: 8px; font-weight: bold;">{{ $order->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px;">Nomor Handphone</td>
                <td style="padding: 8px; font-weight: bold;">{{ $order->user->phone }}</td>
            </tr>
        </table>

        <!-- Detail Pesanan -->
        <h4 style="background: #28a745; color: #fff; padding: 10px; border-radius: 5px;">Detail Pesanan</h4>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #f8f9fa; text-align: left;">
                    <th style="border: 1px solid #ddd; padding: 8px;">No</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Nama Paket</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Kelas</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Jadwal Kelas</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order_package as $item)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $loop->iteration }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->package->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ !is_null($item->class) && $item->class > 0 ? $item->class . 'x Pertemuan' : '-' }}
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $item->dateClass ? $item->dateClass->name : '-' }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">Rp.
                            {{ number_format($item->package->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #ffc107;">
                    <td colspan="4"
                        style="border: 1px solid #ddd; padding: 8px; font-weight: bold; text-align: right;">Total:</td>
                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Rp.
                        {{ number_format($totalPrice, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <!-- Informasi Pembayaran -->
        <h4 style="background: #dc3545; color: #fff; padding: 10px; border-radius: 5px;">Informasi Pembayaran</h4>
        <p>Silakan lakukan pembayaran ke rekening berikut:</p>
        <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 5px;">
            <img src="{{ asset('img/brilogo.png') }}" alt="BRI Logo" style="height: 30px; margin-right: 10px;">
            <span style="font-weight: bold; background: #0A3D91; color: #fff; padding: 5px 10px; border-radius: 3px;">
                Bank BRI
            </span>
        </div>
        <p><strong>Nomor Rekening:</strong> 038501001542300 <strong>(ATLAS KAPITAL PERKASA)</strong> </p>
        <p></p>
        <p><strong>Berita:</strong> <span
                style="color: blue; font-weight: bold;">"BC{{ \Carbon\Carbon::parse($order->created_at)->format('y') }}{{ $order->id }}-{{ $order->user->name }}"</span>
        </p>
        <p style="color: red; font-size: 12px;">Harap gunakan berita transfer agar pembayaran dapat diverifikasi.</p>

        <p style="text-align: center;">Jika ada pertanyaan, silakan hubungi kami.</p>
        <p style="text-align: center;">
            <a href="{{ route('order.detailTransfer', ['id' => $order->id]) }}"
                style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Upload Bukti Pembayaran
            </a>
        </p>
    </div>
</body>


</html>
