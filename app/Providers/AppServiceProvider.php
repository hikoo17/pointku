<?php

namespace App\Providers;

use App\Models\CatatanPoin;
use App\Observers\PoinObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        CatatanPoin::observe(PoinObserver::class);
    }
}
