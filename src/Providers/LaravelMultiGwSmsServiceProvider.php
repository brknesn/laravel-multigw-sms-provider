<?php

namespace Brknesn\LaravelMultiGwSmsProvider\Providers;

use Brknesn\LaravelMultiGwSmsProvider\Facades\SMSFacade;
use Brknesn\LaravelMultiGwSmsProvider\SMSService;
use Illuminate\Support\ServiceProvider;

class LaravelMultiGwSmsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('sms', function () {
            return new SMSService();
        });
    }
    public function boot()
    {
        $this->app->alias('sms', SMSService::class);
        $this->app->alias('sms', SMSFacade::class);
        $this->publishes([
            __DIR__.'/config' => config_path('laravel-multigw-sms-provider'),
        ], 'config');
    }
}
