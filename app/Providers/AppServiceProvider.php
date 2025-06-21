<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        AliasLoader::getInstance()->alias('HStock', \App\Helpers\StockHelper::class);
        AliasLoader::getInstance()->alias('HID', \App\Helpers\IDHelper::class);
        AliasLoader::getInstance()->alias('HDate', \App\Helpers\DateHelper::class);
        AliasLoader::getInstance()->alias('HData', \App\Helpers\DataHelper::class);
        AliasLoader::getInstance()->alias('HUser', \App\Helpers\UserHelper::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
