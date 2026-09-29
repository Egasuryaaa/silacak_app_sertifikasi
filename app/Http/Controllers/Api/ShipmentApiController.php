<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\ShippingCalculationRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentRequest;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentApiController extends Controller
{
    public function __construct(
        protected ShippingCalculationService $calculationService
    ) {}

    public function store(StoreShipmentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $isMember = false;
        if (!empty($validated['customer_id'])) {
            $customer = Customer::find($validated['customer_id']);
            $isMember = (bool) ($customer?->is_member ?? false);
        }

        // Kalkulasi tarif lewat service
        $calcDTO = new ShippingCalculationRequestDTO(
            serviceCode: $validated['service_code'],
            actualWeightKg: (float) $validated['actual_weight'],
            lengthCm: (int) $validated['length_cm'],
            widthCm: (int) $validated['width_cm'],
            heightCm: (int) $validated['height_cm'],
            goodsValue: (float) ($validated['goods_value'] ?? 0),
            isMember: $isMember
        );

        $calcResult = $this->calculationService->calculate($calcDTO);

        $shipment = DB::transaction(function () use ($validated, $calcResult) {
            $trackingNumber = 'SLC' . strtoupper(Str::random(3)) . date('ymd') . rand(1000, 9999);

            $newShipment = Shipment::create([
                'tracking_number'   => $trackingNumber,
                'customer_id'       => $validated['customer_id'] ?? null,
                'origin_branch_id'  => $validated['origin_branch_id'],
                'dest_branch_id'    => $validated['dest_branch_id'],
                'sender_name'       => $validated['sender_name'],
                'sender_phone'      => $validated['sender_phone'],
                'receiver_name'     => $validated['receiver_name'],
                'receiver_phone'    => $validated['receiver_phone'],
                'receiver_address'  => $validated['receiver_address'],
                'service_code'      => $calcResult->serviceCode,
                'actual_weight'     => $validated['actual_weight'],
                'length_cm'         => $validated['length_cm'],
                'width_cm'          => $validated['width_cm'],
                'height_cm'         => $validated['height_cm'],
                'chargeable_weight' => $calcResult->chargeableWeight,
                'goods_value'       => $validated['goods_value'] ?? 0,
                'base_fare'         => $calcResult->baseFare,
                'discount_amount'   => $calcResult->discountAmount,
                'insurance_fee'     => $calcResult->insuranceFee,
                'total_fee'         => $calcResult->totalFee,
                'current_status'    => 'MANIFEST',
            ]);

            TrackingHistory::create([
                'shipment_id' => $newShipment->id,
                'branch_id'   => $validated['origin_branch_id'],
                'status'      => 'MANIFEST',
                'description' => 'Paket diterima di counter asal.',
                'recorded_at' => now(),
            ]);

            return $newShipment;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengiriman berhasil didaftarkan.',
            'data'    => [
                'tracking_number'   => $shipment->tracking_number,
                'chargeable_weight' => $shipment->chargeable_weight,
                'total_fee'         => $shipment->total_fee,
                'label_url'         => route('shipments.print-label', $shipment->tracking_number),
            ],
        ], 201);
    }
}