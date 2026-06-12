<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? setting('app_name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            max-width: 580px;
            margin: 40px auto;
            padding: 0 16px;
        }

        .header {
            text-align: center;
            padding: 32px 0 24px;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #111827;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            display: block;
        }

        .logo-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.3px;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .card-header {
            padding: 32px 32px 24px;
            border-bottom: 1px solid #f3f4f6;
        }

        .card-body {
            padding: 28px 32px;
        }

        .card-footer-inner {
            padding: 20px 32px;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .badge-blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-green {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-red {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-amber {
            background: #fffbeb;
            color: #b45309;
        }

        .badge-gray {
            background: #f9fafb;
            color: #374151;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            line-height: 1.3;
            letter-spacing: -0.3px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f3f4f6;
        }

        .info-table tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 12px 16px;
            font-size: 13px;
            vertical-align: middle;
        }

        .info-table td:first-child {
            color: #9ca3af;
            width: 40%;
            background: #fafafa;
            font-weight: 500;
        }

        .info-table td:last-child {
            color: #111827;
            font-weight: 600;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: #111827;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.1px;
            text-align: center;
        }

        .btn-full {
            display: block;
            text-align: center;
        }

        .btn-outline {
            background: transparent;
            color: #374151 !important;
            border: 1.5px solid #e5e7eb;
        }

        .ticket-card {
            background: #111827;
            border-radius: 16px;
            padding: 24px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .ticket-code {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px 16px;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #ffffff;
            text-align: center;
            border: 1px dashed rgba(255, 255, 255, 0.2);
            margin-top: 16px;
        }

        .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 24px 0;
        }

        .text-sm {
            font-size: 13px;
        }

        .text-xs {
            font-size: 11px;
        }

        .text-gray {
            color: #6b7280;
        }

        .text-green {
            color: #15803d;
        }

        .text-red {
            color: #dc2626;
        }

        .text-amber {
            color: #b45309;
        }

        .font-bold {
            font-weight: 700;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .alert-box {
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
            font-size: 13px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alert-amber {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .alert-green {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #14532d;
        }

        .alert-red {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #7f1d1d;
        }

        .alert-blue {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e3a8a;
        }

        .footer {
            text-align: center;
            padding: 28px 0 40px;
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.8;
        }

        .footer a {
            color: #6b7280;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .social-links {
            margin: 12px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 6px;
            color: #9ca3af;
            font-size: 12px;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .wrapper {
                margin: 20px auto;
            }

            .card-header,
            .card-body,
            .card-footer-inner {
                padding: 20px;
            }

            h1 {
                font-size: 19px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo-wrap">
                <div class="logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="white"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="logo-name">{{ setting('app_name') }}</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="card">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>© {{ date('Y') }} {{ setting('app_name') }}. All rights reserved.</p>
            <div class="social-links">
                @if (setting('social_instagram'))
                    <a href="{{ setting('social_instagram') }}">Instagram</a>
                @endif
                @if (setting('social_twitter'))
                    <a href="{{ setting('social_twitter') }}">Twitter</a>
                @endif
                @if (setting('social_facebook'))
                    <a href="{{ setting('social_facebook') }}">Facebook</a>
                @endif
            </div>
            <p>
                {{ setting('app_address', 'Indonesia') }}
            </p>
            <p style="margin-top: 8px;">
                <a href="{{ route('pages.privacy') }}">Kebijakan Privasi</a>
                &nbsp;·&nbsp;
                <a href="{{ route('pages.terms') }}">Syarat & Ketentuan</a>
                &nbsp;·&nbsp;
                <a href="{{ route('faqs.index') }}">FAQ</a>
            </p>
            <p style="margin-top: 12px; font-size: 11px;">
                Email ini dikirim ke <strong>{{ $recipientEmail ?? '' }}</strong> karena Anda
                terdaftar di {{ setting('app_name') }}.
            </p>
        </div>

    </div>
</body>

</html>
