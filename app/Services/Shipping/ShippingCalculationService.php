<?php

namespace App\Services\Shipping;

use App\Contracts\ShippingServiceStrategyInterface;
use App\DataTransferObjects\ShippingCalculationRequestDTO;
use App\DataTransferObjects\ShippingCalculationResultDTO;
use InvalidArgumentException;

class ShippingCalculationService
{
    /** @var array<string, ShippingServiceStrategyInterface> */
    protected array $strategies = [];

    public function __construct(iterable $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->strategies[strtoupper($strategy->getServiceCode())] = $strategy;
        }
    }

    public function calculate(ShippingCalculationRequestDTO $dto): ShippingCalculationResultDTO
    {
        $strategy = $this->resolveStrategy($dto->serviceCode);

        // Subrutin 1: Berat Tagih dibulatkan ke atas (perbaikan bug legacy floor -> ceil)
        $chargeableWeight = $this->calculateChargeableWeight(
            $dto->actualWeightKg,
            $dto->lengthCm,
            $dto->widthCm,
            $dto->heightCm
        );

        // Subrutin 2: Tarif dasar polimorfis
        $baseFare = $strategy->calculateBaseFare($chargeableWeight);

        // Subrutin 3: Diskon member 10%
        $discountAmount = $this->calculateMemberDiscount($baseFare, $dto->isMember);

        // Subrutin 4: Asuransi 0.2% jika nilai barang > 1.000.000
        $insuranceFee = $this->calculateInsuranceFee($dto->goodsValue);

        // Subrutin 5: Total akhir
        $totalFee = ($baseFare - $discountAmount) + $insuranceFee;

        return new ShippingCalculationResultDTO(
            serviceCode: $strategy->getServiceCode(),
            chargeableWeight: $chargeableWeight,
            baseFare: $baseFare,
            discountAmount: $discountAmount,
            insuranceFee: $insuranceFee,
            totalFee: $totalFee
        );
    }

    public function calculateChargeableWeight(float $actualWeight, int $l, int $w, int $h): int
    {
        $volumetricWeight = ($l * $w * $h) / 6000.0;
        return (int) ceil(max($actualWeight, $volumetricWeight));
    }

    public function calculateMemberDiscount(float $baseFare, bool $isMember): float
    {
        return $isMember ? round($baseFare * 0.10, 2) : 0.0;
    }

    public function calculateInsuranceFee(float $goodsValue): float
    {
        return ($goodsValue > 1000000.0) ? round($goodsValue * 0.002, 2) : 0.0;
    }

    protected function resolveStrategy(string $serviceCode): ShippingServiceStrategyInterface
    {
        $code = strtoupper($serviceCode);
        if (!isset($this->strategies[$code])) {
            throw new InvalidArgumentException("Layanan [{$serviceCode}] tidak dikenali.");
        }
        return $this->strategies[$code];
    }
}