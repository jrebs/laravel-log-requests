<?php

namespace Jrebs\LogRequests\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Jrebs\LogRequests\Http\Middleware\LogRequests;

class LogRequestsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../config/log-requests.php' => config_path('log-requests.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/log-requests.php',
            'log-requests'
        );

        Route::aliasMiddleware('log-requests', LogRequests::class);
    }
}
