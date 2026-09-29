<?php

namespace App\DataTransferObjects;

readonly class ShippingCalculationResultDTO
{
    public function __construct(
        public string $serviceCode,
        public int $chargeableWeight,
        public float $baseFare,
        public float $discountAmount,
        public float $insuranceFee,
        public float $totalFee
    ) {}

    public function toArray(): array
    {
        return [
            'service_code'      => $this->serviceCode,
            'chargeable_weight' => $this->chargeableWeight,
            'base_fare'         => $this->baseFare,
            'discount_amount'   => $this->discountAmount,
            'insurance_fee'     => $this->insuranceFee,
            'total_fee'         => $this->totalFee,
        ];
    }
}