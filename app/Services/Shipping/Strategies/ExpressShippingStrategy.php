<?php

namespace App\Services\Shipping\Strategies;

use App\Contracts\ShippingServiceStrategyInterface;

class ExpressShippingStrategy implements ShippingServiceStrategyInterface
{
    public function getServiceCode(): string { return 'EXP'; }
    public function getRatePerKg(): float { return 15000.0; }
    public function getMinimumWeight(): int { return 1; }

    public function calculateBaseFare(int $billableWeight): float
    {
        $weight = max($billableWeight, $this->getMinimumWeight());
        return $weight * $this->getRatePerKg();
    }
}