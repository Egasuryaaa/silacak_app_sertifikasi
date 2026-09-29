<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Resi - {{ $shipment->tracking_number }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; margin: 0; padding: 8px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 4px; }
        .logo { font-size: 14px; font-weight: bold; }
        .barcode-box { text-align: center; margin: 6px 0; }
        .barcode-img { width: 85%; height: 40px; }
        .tracking-num { font-size: 13px; font-weight: bold; margin-top: 2px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .info-table td { border: 1px solid #333; padding: 4px; vertical-align: top; }
        .service-badge { font-size: 13px; font-weight: bold; background-color: #000; color: #fff; padding: 2px 5px; text-align: center; }
        .qrcode-box { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <span class="logo">SiLacak - PT Sinar Logistik Nusantara</span>
    </div>

    <div class="barcode-box">
        <img class="barcode-img" src="{{ $barcode }}" alt="Barcode"><br>
        <span class="tracking-num">{{ $shipment->tracking_number }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong>PENGIRIM:</strong><br>
                {{ $shipment->sender_name }}<br>
                Telp: {{ $shipment->sender_phone }}<br>
                Asal: {{ $shipment->originBranch->name }}
            </td>
            <td style="width: 50%;">
                <strong>PENERIMA:</strong><br>
                {{ $shipment->receiver_name }}<br>
                {{ $shipment->receiver_address }}<br>
                Telp: {{ $shipment->receiver_phone }}<br>
                Tujuan: {{ $shipment->destBranch->name }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: middle;">
                <div class="service-badge">{{ $shipment->service_code }}</div>
                <p style="margin: 4px 0 0 0;">Berat Tagih: <strong>{{ $shipment->chargeable_weight }} Kg</strong></p>
                <p style="margin: 2px 0 0 0;">Total Biaya: <strong>Rp {{ number_format($shipment->total_fee, 0, ',', '.') }}</strong></p>
            </td>
            <td class="qrcode-box">
                <img src="{{ $qrcode }}" width="70" height="70"><br>
                <span style="font-size: 8px;">Scan Lacak Cepat</span>
            </td>
        </tr>
    </table>
</body>
</html>