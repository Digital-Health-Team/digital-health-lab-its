<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Dikonfirmasi — {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#F8F9FA;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F9FA;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,66,109,0.10);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#00426D 0%,#00A8B5 100%);padding:40px 48px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <p style="margin:0;font-size:22px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">
                                            {{ config('app.name') }}
                                        </p>
                                        <p style="margin:4px 0 0;font-size:12px;color:rgba(255,255,255,0.7);letter-spacing:0.5px;text-transform:uppercase;">
                                            Institut Teknologi Sepuluh Nopember
                                        </p>
                                    </td>
                                    <td align="right">
                                        <span style="display:inline-block;background:rgba(255,255,255,0.15);border-radius:50%;width:48px;height:48px;line-height:48px;text-align:center;font-size:22px;">
                                            🎓
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Yellow accent bar --}}
                    <tr>
                        <td style="height:4px;background:#FFC72C;"></td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:48px 48px 32px;">
                            <p style="margin:0 0 8px;font-size:24px;font-weight:700;color:#1E293B;line-height:1.3;">
                                Selamat, {{ $registration->full_name }}!
                            </p>
                            <p style="margin:0 0 28px;font-size:15px;color:#64748B;line-height:1.6;">
                                Pendaftaran Anda untuk workshop berikut telah <strong style="color:#00426D;">dikonfirmasi</strong> oleh admin kami.
                            </p>

                            {{-- Training detail box --}}
                            <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:28px;">
                                <tr>
                                    <td style="background:#F0F9FF;border-left:4px solid #00A8B5;border-radius:0 8px 8px 0;padding:18px 20px;">
                                        <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#0F172A;line-height:1.4;">
                                            {{ $training->title }}
                                        </p>
                                        @if($training->date)
                                        <p style="margin:4px 0 0;font-size:13px;color:#475569;">
                                            📅 {{ $training->date->format('d M Y, H:i') }} WIB
                                        </p>
                                        @endif
                                        @if($training->location)
                                        <p style="margin:4px 0 0;font-size:13px;color:#475569;">
                                            📍 {{ $training->location }}
                                        </p>
                                        @endif
                                        @if($training->instructor_name)
                                        <p style="margin:4px 0 0;font-size:13px;color:#475569;">
                                            👤 {{ $training->instructor_name }}
                                            @if($training->instructor_title)
                                                — {{ $training->instructor_title }}
                                            @endif
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 12px;font-size:15px;color:#64748B;line-height:1.6;">
                                Harap pastikan Anda hadir tepat waktu. Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.
                            </p>

                            <p style="margin:0;font-size:14px;color:#94A3B8;line-height:1.6;">
                                Email konfirmasi ini dikirim ke <strong>{{ $registration->email }}</strong>.
                            </p>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td style="padding:0 48px;">
                            <hr style="border:none;border-top:1px solid #E2E8F0;margin:0;" />
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:28px 48px 40px;">
                            <p style="margin:0 0 4px;font-size:12px;color:#94A3B8;line-height:1.6;">
                                Email ini dikirim secara otomatis oleh sistem <strong>{{ config('app.name') }}</strong>.
                                Mohon tidak membalas email ini.
                            </p>
                            <p style="margin:0;font-size:12px;color:#CBD5E1;">
                                © {{ date('Y') }} Institut Teknologi Sepuluh Nopember. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
