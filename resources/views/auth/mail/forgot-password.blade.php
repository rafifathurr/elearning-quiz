<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
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
                            <h2 style="color: #007bff; margin: 0;">Reset Password</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 16px; color: #333;">Halo, <strong>{{ $user->name }}</strong></p>
                            <p style="font-size: 14px; color: #555;">Anda telah meminta reset password. Klik tombol di
                                bawah ini untuk mereset password Anda:</p>

                            <div style="text-align: center; margin: 20px 0;">
                                <a href="{{ url('password/reset-password/' . $token) }}"
                                    style="padding: 12px 20px; background: #007bff; color: white; text-decoration: none; font-size: 16px; border-radius: 5px; display: inline-block; font-weight: bold;">
                                    Reset Password
                                </a>
                            </div>

                            <p style="font-size: 14px; color: #555;">Jika Anda tidak meminta reset password, abaikan
                                email ini.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
