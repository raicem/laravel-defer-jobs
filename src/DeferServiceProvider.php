<?php

namespace Raicem\Defer;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferServiceProvider extends ServiceProvider implements DeferrableServiceProvider
{
    public function register()
    {
        $this->app->singleton(Deferrer::class, function ($app) {
            return new Deferrer();
        });
    }

    public function provides()
    {
        return [Deferrer::class];
    } 
}
