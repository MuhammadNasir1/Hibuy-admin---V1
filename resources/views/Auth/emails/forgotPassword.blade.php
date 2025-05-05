@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="padding: 40px; text-align: center; background-color: #4A90E2; color: white;">
                            <h1 style="margin: 0; font-size: 24px;">Password Reset Request</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333;">Hello, <strong>{{ Str::title($name) }}</strong></p>
                            <p style="font-size: 16px; color: #333;">You requested a password reset. Click the button
                                below to reset your password:</p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('forgot.password.Link', ['token' => $token]) }}?email={{ urlencode($email) }}"
                                    style="background-color: #4A90E2; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                                    Reset Password
                                </a>
                            </p>
                            <p style="font-size: 14px; color: #666;">This link will expire in 5 minutes. If you did not
                                request a password reset, please ignore this email.</p>
                            <p style="font-size: 14px; color: #666;">Thank you,<br>Hibuyo Team</p>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 20px; text-align: center; font-size: 12px; color: #aaa; background-color: #f9f9f9;">
                            &copy; {{ date('Y') }} Hibuyo. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
