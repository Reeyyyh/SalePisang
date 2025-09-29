<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Verifikasi</title>
</head>
<body style="background-color: #f3f4f6; font-family: sans-serif; color: #1f2937; padding: 40px 16px;">
    <div style="max-width: 480px; margin: auto; background-color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="padding: 24px; text-align: center;">
            <h1 style="font-size: 24px; font-weight: bold; color: #1e3a8a; margin-bottom: 8px;">Verifikasi Akun Anda</h1>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">Structura E-Commerce</p>

            <p style="font-size: 16px; color: #374151; margin-bottom: 8px;">
                Halo, <strong>{{ $user->name }}</strong> 👋
            </p>

            <p style="font-size: 14px; color: #374151; margin-bottom: 16px; line-height: 1.6;">
                Terima kasih telah mendaftar di <strong>Structura</strong>. Untuk mengaktifkan akun Anda, klik tombol di bawah ini untuk melakukan verifikasi.
            </p>

            <a href="{{ $verificationUrl }}" style="display: inline-block; width: 100%; max-width: 200px; background-color: #1e3a8a; color: white; font-weight: 500; padding: 12px 24px; border-radius: 8px; margin-top: 16px; text-decoration: none;">
                Verifikasi Akun
            </a>

            <p style="font-size: 12px; color: #6b7280; margin-top: 24px;">
                Jika Anda tidak merasa mendaftar di Structura, abaikan email ini.
            </p>

            <p style="font-size: 12px; color: #9ca3af; margin-top: 16px;">
                © 2025 Structura. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
