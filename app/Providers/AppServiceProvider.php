<?php

namespace App\Providers;

use PHPGangsta_GoogleAuthenticator;
use App\Services\Markdown\Markdown;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // DB query log
        if (config('app.env') == 'local') {
            app('db')->enableQueryLog();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Google authenticator
        $this->app->singleton('gauth', function () {
            return new PHPGangsta_GoogleAuthenticator();
        });

        // Markdown parser
        $this->app->singleton('markdown', function() {
            return new Markdown();
        });
    }
}
