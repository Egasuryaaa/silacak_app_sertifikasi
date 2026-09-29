<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Services\Label\ShipmentLabelService;
use Illuminate\Http\Response;

class LabelPrinterController extends Controller
{
    public function __construct(
        protected ShipmentLabelService $labelService
    ) {}

    public function printLabel(string $trackingNumber): Response
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)
            ->with(['originBranch', 'destBranch', 'customer'])
            ->firstOrFail();

        $pdf = $this->labelService->generateLabelPdf($shipment);

        return $pdf->stream("Label-{$shipment->tracking_number}.pdf");
    }
}