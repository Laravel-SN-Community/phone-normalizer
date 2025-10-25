<?php

namespace Laravelsn\PhoneNormalizer\Providers;

use Illuminate\Support\ServiceProvider;
use Laravelsn\PhoneNormalizer\PhoneNormalizerManager;

class PhoneNormalizerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('phone.normilizer', PhoneNormalizerManager::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/phonenormalizer.php' => config_path('phonenormalizer.php'),
        ], 'phonenormalizer-config');
    }
}
