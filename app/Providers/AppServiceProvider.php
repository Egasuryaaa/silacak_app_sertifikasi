<?php

namespace App\Providers;

use App\Services\Shipping\ShippingCalculationService;
use App\Services\Shipping\Strategies\ExpressShippingStrategy;
use App\Services\Shipping\Strategies\KargoShippingStrategy;
use App\Services\Shipping\Strategies\RegulerShippingStrategy;
use App\Services\Shipping\Strategies\SameDayShippingStrategy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ShippingCalculationService::class, function ($app) {
            return new ShippingCalculationService([
                $app->make(RegulerShippingStrategy::class),
                $app->make(ExpressShippingStrategy::class),
                $app->make(KargoShippingStrategy::class),
                $app->make(SameDayShippingStrategy::class),
            ]);
        });
    }

    public function boot(): void
    {
        //
    }
}