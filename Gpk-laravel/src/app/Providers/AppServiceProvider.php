<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

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
        // 本番環境でHTTPSを強制
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // カートアイテム数をすべてのビューで共有
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())
                    ->with('cartDetails')
                    ->first();
                
                $cart_item_count = $cart ? $cart->cartDetails->count() : 0;
                $view->with('cart_item_count', $cart_item_count);
            } else {
                $view->with('cart_item_count', 0);
            }
        });
    }
}
