<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
</head>
<body style="background-color: #f3f4f6; font-family: sans-serif; color: #1f2937; padding: 40px 16px;">
    <div style="max-width: 480px; margin: auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 24px; text-align: center;">
            <!-- Title -->
            <h1 style="font-size: 24px; font-weight: bold; color: #1e3a8a; margin-bottom: 8px;">
                Reset Password
            </h1>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                Structura E-Commerce
            </p>

            <!-- Greeting -->
            <p style="font-size: 16px; color: #374151; margin-bottom: 8px;">
                Halo, <strong>{{ $user->name }}</strong> 👋
            </p>

            <!-- Body Text -->
            <p style="font-size: 14px; color: #374151; margin-bottom: 16px; line-height: 1.6;">
                Kami menerima permintaan untuk mereset kata sandi akun Anda di
                <strong>Structura</strong>. Jika Anda memang meminta perubahan ini,
                silakan klik tombol di bawah untuk melanjutkan proses reset password.
            </p>

            <!-- CTA Button -->
            <a href="{{ $resetUrl }}" style="display: inline-block; width: 100%; max-width: 200px; background-color: #1e3a8a; color: white; font-weight: 500; padding: 12px 24px; border-radius: 8px; margin-top: 16px; text-decoration: none;">
                Reset Password
            </a>

            <!-- Disclaimer -->
            <p style="font-size: 12px; color: #6b7280; margin-top: 24px;">
                Jika Anda tidak pernah meminta reset password, abaikan email ini. Akun Anda tetap aman.
            </p>

            <!-- Footer -->
            <p style="font-size: 12px; color: #9ca3af; margin-top: 16px;">
                © 2025 Structura. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
