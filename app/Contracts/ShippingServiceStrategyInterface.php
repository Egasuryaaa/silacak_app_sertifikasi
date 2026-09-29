<?php

namespace App\Contracts;

interface ShippingServiceStrategyInterface
{
    public function getServiceCode(): string;
    public function getRatePerKg(): float;
    public function getMinimumWeight(): int;
    public function calculateBaseFare(int $billableWeight): float;
}