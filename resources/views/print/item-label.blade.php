<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label: {{ $item['unique_code'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Inter:wght@400;600;700&display=swap');

        @page {
            size: 58mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            width: 58mm;
            background: #fff;
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .label {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 58mm;
            min-height: 30mm;
            padding: 2mm;
            gap: 2mm;
            border: 0.3mm solid #000;
        }

        .qr-block {
            flex-shrink: 0;
            width: 24mm;
            height: 24mm;
        }

        .qr-block svg {
            width: 24mm;
            height: 24mm;
            display: block;
        }

        .info-block {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1mm;
            overflow: hidden;
        }

        .org-tag {
            font-size: 4.5pt;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }

        .item-name {
            font-size: 6pt;
            font-weight: 700;
            color: #111;
            line-height: 1.2;
            word-break: break-word;
        }

        .item-subtitle {
            font-size: 5pt;
            color: #6b7280;
        }

        .unique-code {
            font-family: 'Courier Prime', 'Courier New', monospace;
            font-size: 5pt;
            font-weight: 700;
            color: #000;
            letter-spacing: 0.3pt;
            margin-top: 1mm;
            word-break: break-all;
        }

        .scan-hint {
            font-size: 4pt;
            color: #9ca3af;
            margin-top: 0.5mm;
        }

        @media screen {
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background: #f3f4f6;
            }

            .label {
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border-radius: 2mm;
            }
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="qr-block">
            {!! $qrSvg !!}
        </div>

        <div class="info-block">
            <div class="org-tag">Digital Health Lab ITS</div>
            <div class="item-name">{{ $item['name'] }}</div>
            @if(!empty($item['subtitle']))
                <div class="item-subtitle">{{ $item['subtitle'] }}</div>
            @endif
            <div class="unique-code">{{ $item['unique_code'] }}</div>
            <div class="scan-hint">Scan QR untuk detail</div>
        </div>
    </div>

    <script>
        window.onload = function () { window.print(); };
    </script>
</body>
</html>
