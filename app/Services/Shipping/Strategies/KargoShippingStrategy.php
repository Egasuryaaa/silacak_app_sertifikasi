<?php

namespace App\Services\Shipping\Strategies;

use App\Contracts\ShippingServiceStrategyInterface;

class KargoShippingStrategy implements ShippingServiceStrategyInterface
{
    public function getServiceCode(): string { return 'KGO'; }
    public function getRatePerKg(): float { return 6000.0; }
    public function getMinimumWeight(): int { return 10; } // Skenario: Kargo minimal 10 kg

    public function calculateBaseFare(int $billableWeight): float
    {
        $weight = max($billableWeight, $this->getMinimumWeight());
        return $weight * $this->getRatePerKg();
    }
}