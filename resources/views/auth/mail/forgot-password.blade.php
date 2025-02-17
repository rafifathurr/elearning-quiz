<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
</head>

<body>
    <p>Halo, {{ $user->name }}</p>
    <p>Anda telah meminta reset password. Klik tombol di bawah ini untuk mereset password Anda:</p>
    <a href="{{ url('password/reset-password/' . $token) }}"
        style="padding: 10px; background: #007bff; color: white; text-decoration: none; display: inline-block;">Reset
        Password</a>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

</body>

</html>
