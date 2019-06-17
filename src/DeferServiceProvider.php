<?php

namespace Raicem\Defer;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Deferrer::class, function ($app) {
            return new Deferrer();
        });
    }
}