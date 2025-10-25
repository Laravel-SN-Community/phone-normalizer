<?php

namespace Tests;

use Laravelsn\PhoneNormalizer\Facades\PhoneNormalizer;
use Laravelsn\PhoneNormalizer\Providers\PhoneNormalizerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PhoneNormalizerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Phone' => PhoneNormalizer::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('phonenormalizer.default_country', 'SN');
    }
}
