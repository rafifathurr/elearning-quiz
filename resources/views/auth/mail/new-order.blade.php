<!DOCTYPE html>
<html>

<head>
    <title>Pemberitahuan Pesanan Baru</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600px"
                    style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <h2 style="color: #007bff; margin: 0;">Pesanan Baru</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 16px; color: #333;">Halo, <strong>Admin</strong></p>
                            <p style="font-size: 14px; color: #555;">Anda memiliki pesanan baru dengan rincian:</p>

                            <ul>
                                <li>Nama Pemesan: {{ $order->user->name }}</li>
                                <li>Email Pemesan: {{ $order->user->email }}</li>
                                <li>Total Harga: Rp. {{ number_format($order->total_price, 0, ',', '.') }}</li>
                            </ul>

                            <p>Klik tombol di bawah ini untuk konfirmasi</p>
                            <a href="{{ route('order.detailOrder', ['id' => $order->id]) }}"
                                style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                                Konfirmasi Pembayaran
                            </a>


                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
