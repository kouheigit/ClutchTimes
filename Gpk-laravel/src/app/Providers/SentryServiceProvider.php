<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sentry\Laravel\ServiceProvider as SentryServiceProvider;

class SentryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Sentryがインストールされている場合のみ登録
        if (class_exists(SentryServiceProvider::class) && config('sentry.dsn')) {
            $this->app->register(SentryServiceProvider::class);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}



















