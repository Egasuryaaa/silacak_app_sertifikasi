<?php

namespace App\Services\Shipping\Strategies;

use App\Contracts\ShippingServiceStrategyInterface;

class SameDayShippingStrategy implements ShippingServiceStrategyInterface
{
    public function getServiceCode(): string { return 'SD'; }
    public function getRatePerKg(): float { return 25000.0; }
    public function getMinimumWeight(): int { return 1; }

    public function calculateBaseFare(int $billableWeight): float
    {
        $weight = max($billableWeight, $this->getMinimumWeight());
        return $weight * $this->getRatePerKg();
    }
}