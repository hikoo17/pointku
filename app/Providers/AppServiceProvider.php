<?php

namespace App\Providers;

use App\Models\CatatanPoin;
use App\Observers\PoinObserver;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
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

        $locale = $this->app->getLocale();
        Carbon::setLocale($locale);
        SupportCarbon::setLocale($locale);
    }
}
