<?php

namespace AdamczykPiotr\MakeTransformer;

use Illuminate\Support\ServiceProvider;
use AdamczykPiotr\MakeTransformer\Commands\MakeTransformer;

class TransformerMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot() {}

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('command.make:transformer', MakeTransformer::class);

        $this->commands(['command.make:transformer']);
    }
}
