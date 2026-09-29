<?php

namespace App\Services\Shipping\Strategies;

use App\Contracts\ShippingServiceStrategyInterface;

class RegulerShippingStrategy implements ShippingServiceStrategyInterface
{
    public function getServiceCode(): string { return 'REG'; }
    public function getRatePerKg(): float { return 9000.0; }
    public function getMinimumWeight(): int { return 1; }

    public function calculateBaseFare(int $billableWeight): float
    {
        $weight = max($billableWeight, $this->getMinimumWeight());
        return $weight * $this->getRatePerKg();
    }
}