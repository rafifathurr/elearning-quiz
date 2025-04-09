<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Order Ditolak</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333">
    <div
        style="max-width: 600px; background: #fff; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)">
        <h2 style="text-align: center; color: red">Order Ditolak</h2>
        <hr />
        <p style="text-align: left; margin-top: 20px">Hallo, <strong>{{ $order->user->name }}</strong></p>

        <p>
            Kami mohon maaf, pesanan kamu dengan ID
            <strong>"BC{{ \Carbon\Carbon::parse($order->created_at)->format('y') }}{{ $order->id }}"</strong>
            <strong>tidak dapat kami proses</strong> saat ini.
        </p>

        <p>Alasan penolakan: <strong>{{ $order->reason }}</strong></p>



        <p>Jangan khawatir! Kamu masih bisa melanjutkan proses pemesanan dengan mengupload ulang bukti pembayaran
            melalui website <strong>Brata Cerdas</strong>.</p>

        <p style="text-align: center">
            <a href="{{ route('login') }}"
                style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold">
                Login Brata Cerdas </a>
        </p>
    </div>
</body>

</html>
