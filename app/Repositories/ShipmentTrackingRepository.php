<?php

namespace App\Repositories;

use App\Models\Shipment;
use Illuminate\Support\Facades\Cache;

class ShipmentTrackingRepository
{
    public const CACHE_TTL_SECONDS = 600; // 10 menit

    public function findByTrackingNumber(string $trackingNumber): ?array
    {
        $cacheKey = "silacak:tracking:{$trackingNumber}";

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($trackingNumber) {
            // Prepared statement terproteksi melalui parameter binding Eloquent
            $shipment = Shipment::query()
                ->where('tracking_number', $trackingNumber)
                ->with([
                    'originBranch:id,code,name,city',
                    'destBranch:id,code,name,city',
                    'trackingHistories.branch:id,code,name',
                ])
                ->first();

            if (!$shipment) {
                return null;
            }

            return [
                'tracking_number'   => $shipment->tracking_number,
                'service'           => $shipment->service_code,
                'status'            => $shipment->current_status,
                'chargeable_weight' => $shipment->chargeable_weight,
                'origin'            => $shipment->originBranch->name,
                'destination'       => $shipment->destBranch->name,
                'sender'            => $shipment->sender_name,
                'receiver'          => $shipment->receiver_name,
                'created_at'        => $shipment->created_at->toIso8601String(),
                'histories'         => $shipment->trackingHistories->map(fn($item) => [
                    'status'      => $item->status,
                    'location'    => $item->branch?->name ?? 'Hub Transit',
                    'description' => $item->description,
                    'timestamp'   => $item->recorded_at->toIso8601String(),
                ])->toArray(),
            ];
        });
    }

    public function invalidateCache(string $trackingNumber): void
    {
        Cache::forget("silacak:tracking:{$trackingNumber}");
    }
}