<?php

namespace Laravelsn\PhoneNormalizer\Providers;

use Illuminate\Support\ServiceProvider;
use Laravelsn\PhoneNormalizer\PhoneNormalizerManager;

class PhoneNormalizerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/phonenormalizer.php',
            'phonenormalizer'
        );

        $this->app->singleton('phone.normilizer', function ($app) {
            return new PhoneNormalizerManager($app);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/phonenormalizer.php' => config_path('phonenormalizer.php'),
        ], 'phonenormalizer-config');
    }
}
