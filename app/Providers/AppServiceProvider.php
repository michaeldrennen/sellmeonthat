<?php

namespace App\Providers;

use App\Models\Offer;
use App\Models\Want;
use App\Policies\OfferPolicy;
use App\Policies\WantPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        Want::class => WantPolicy::class,
        Offer::class => OfferPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
