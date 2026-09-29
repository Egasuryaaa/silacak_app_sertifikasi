<?php

namespace App\DataTransferObjects;

readonly class ShippingCalculationRequestDTO
{
    public function __construct(
        public string $serviceCode,
        public float $actualWeightKg,
        public int $lengthCm,
        public int $widthCm,
        public int $heightCm,
        public float $goodsValue,
        public bool $isMember
    ) {}
}