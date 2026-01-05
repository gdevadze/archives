<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <title>დოკუმენტის ეტიკეტი</title>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #eef1f5;
        }

        .label {
            width: 420px;   /* ~105mm */
            height: 260px;  /* ~65mm */
            background: #fff;
            border-radius: 10px;
            border: 2px solid #111827;
            padding: 16px;
            margin: 40px auto;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #d1d5db;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .header-title {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 1px;
            color: #111827;
        }

        .header-sub {
            font-size: 11px;
            color: #6b7280;
        }

        .content {
            font-size: 12px;
            line-height: 1.5;
        }

        .content b {
            color: #111827;
        }

        .companies {
            font-size: 11px;
            color: #374151;
            margin-top: 4px;
        }

        .barcode-wrapper {
            margin-top: 10px;
            text-align: center;
            border-top: 1px dashed #d1d5db;
            padding-top: 8px;
        }

        .barcode-text {
            font-size: 11px;
            letter-spacing: 2px;
            margin-top: 4px;
            color: #111827;
        }

        .no-print {
            text-align: center;
            margin-top: 16px;
        }

        @media print {
            body {
                background: #fff;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="label">
    <div class="header">
        <div>
            <div class="header-title">DOCUMENT ARCHIVE</div>
        </div>
        <div class="header-sub">{{ $document->contract_date->format('d.m.Y') }}</div>
    </div>

    <div class="content">
        <div><b>დოკ. №:</b> {{ $document->title }}</div>
        <div><b>ტიპი:</b> {{ $document->contractType->contract_type_name ?? '-' }}</div>
        <div><b>წელი:</b> {{ $document->year }}</div>
        <div><b>თარიღი:</b> {{ $document->contract_date }}</div>

        <div class="companies">
            <b>კომპანიები:</b>
            {{ $document->companies->pluck('company_name')->join(', ') }}
        </div>
    </div>

    <div class="barcode-wrapper">
        <svg id="barcode"></svg>
    </div>
</div>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-primary">
        🖨️ ბეჭდვა
    </button>
</div>

<script>
    JsBarcode("#barcode", "{{ $document->id }}", {

        width: 2,
        height: 70,
        displayValue: false,
        margin: 0
    });
</script>

</body>
</html>
