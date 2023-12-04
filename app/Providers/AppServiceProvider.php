<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// TODO: Fix max key length is 1000 bytes
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // TODO: Fix max key length is 1000 bytes
        Schema::defaultStringLength(191);
    }
}
