<?php

namespace App\Services\Label;

use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShipmentLabelService
{
    public function generateLabelPdf(Shipment $shipment)
    {
        // Generate Barcode 1D Code 128
        $barcodeGenerator = new BarcodeGeneratorPNG();
        $barcodeData = $barcodeGenerator->getBarcode(
            $shipment->tracking_number,
            $barcodeGenerator::TYPE_CODE_128,
            2,
            55
        );
        $barcodeBase64 = 'data:image/png;base64,' . base64_encode($barcodeData);

        // Generate QR Code Tracking URL
        $trackingUrl = url("/track?resi={$shipment->tracking_number}");
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(90)->generate($trackingUrl));
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . $qrCodeSvg;

        return Pdf::loadView('labels.shipping_label', [
            'shipment' => $shipment,
            'barcode'  => $barcodeBase64,
            'qrcode'   => $qrCodeBase64,
        ])->setPaper([0, 0, 283.46, 425.20], 'portrait');
    }
}