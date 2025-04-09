<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Approve Order</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333">
    <div
        style="max-width: 600px; background: #fff; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)">
        <h2 style="text-align: center; color: green">Approve Order</h2>
        <hr />
        <p style="text-align: left; margin-top: 20px">Hallo, <strong>{{ $order->user->name }}</strong></p>

        <p>
            Selamat! 🎉 Pesanan kamu dengan ID
            <strong>"BC{{ \Carbon\Carbon::parse($order->created_at)->format('y') }}{{ $order->id }}"</strong>
            telah <strong>berhasil disetujui</strong> oleh admin kami.
        </p>

        <!-- Detail Pesanan -->
        <h4 style="background: #28a745; color: #fff; padding: 10px; border-radius: 5px">Detail Pesanan</h4>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px">
            <thead>
                <tr style="background: #f8f9fa; text-align: left">
                    <th style="border: 1px solid #ddd; padding: 8px">No</th>
                    <th style="border: 1px solid #ddd; padding: 8px">Nama Paket</th>
                    <th style="border: 1px solid #ddd; padding: 8px">Kelas</th>
                    <th style="border: 1px solid #ddd; padding: 8px">Jadwal Kelas</th>
                    <th style="border: 1px solid #ddd; padding: 8px">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order_package as $item)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px">{{ $loop->iteration }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px">{{ $item->package->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px">
                            {{ !is_null($item->class) && $item->class > 0 ? $item->class . 'x Pertemuan' : '-' }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px">
                            {{ $item->dateClass ? $item->dateClass->name : '-' }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px">Rp.
                            {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #ffc107">
                    <td colspan="4"
                        style="border: 1px solid #ddd; padding: 8px; font-weight: bold; text-align: right">Total:</td>
                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold">Rp.
                        {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <!-- Informasi Pembayaran -->

        <p>Silakan login ke website Brata Cerdas untuk mengerjakan test-test yang ada 💪</p>

        <p style="text-align: center">
            <a href="{{ route('login') }}"
                style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold">
                Login Brata Cerdas </a>
        </p>
    </div>
</body>

</html>
