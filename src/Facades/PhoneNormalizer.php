<?php

namespace Laravelsn\PhoneNormalizer\Facades;

use Illuminate\Support\Facades\Facade;

class PhoneNormalizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'phonenormalizer';
    }
}
