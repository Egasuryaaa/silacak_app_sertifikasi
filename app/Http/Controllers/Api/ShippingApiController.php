<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\ShippingCalculationRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateShippingRequest;
use App\Models\Customer;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Http\JsonResponse;

class ShippingApiController extends Controller
{
    public function __construct(
        protected ShippingCalculationService $calculationService
    ) {}

    public function calculate(CalculateShippingRequest $request): JsonResponse
    {
        $isMember = false;
        if ($request->filled('customer_id')) {
            $customer = Customer::find($request->customer_id);
            $isMember = (bool) ($customer?->is_member ?? false);
        }

        $dto = new ShippingCalculationRequestDTO(
            serviceCode: $request->validated('service_code'),
            actualWeightKg: (float) $request->validated('actual_weight'),
            lengthCm: (int) $request->validated('length'),
            widthCm: (int) $request->validated('width'),
            heightCm: (int) $request->validated('height'),
            goodsValue: (float) ($request->validated('goods_value') ?? 0),
            isMember: $isMember
        );

        $result = $this->calculationService->calculate($dto);

        return response()->json([
            'status'  => 'success',
            'message' => 'Perhitungan ongkir berhasil.',
            'data'    => $result->toArray(),
        ]);
    }
}