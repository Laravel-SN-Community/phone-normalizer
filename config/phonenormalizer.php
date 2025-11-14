<?php

return [
    'default_country' => env('PHONE_NORMALIZER_DEFAULT_COUNTRY', 'SN'),
    'countries' => [
        'SN' => [
            'code' => '+221',
            'pattern' => '/^(7[05678][0-9]{7})$/',
            'length' => 9,
        ],
        // Structure prête pour d'autres pays
        'CI' => [
            'code' => '+225',
            'pattern' => '/^(0[157]|2[57])[0-9]{8}$/',
            'length' => 10,
        ],
        'ML' => [
            'code' => '+223',
            'pattern' => '/^[0-9]+$/',
            'length' => 8,
        ],
        'GM' => [
            'code' => '+220',
            'pattern' => '/^[0-9]+$/',
            'length' => 7,
        ],
        'BF' => [
            'code' => '+226',
            'pattern' => '/^[0-9]+$/',
            'length' => 8,
        ],
        'BJ' => [
            'code' => '+229',
            'pattern' => '/^[0-9]+$/',
            'length' => 8,
        ],
        'TG' => [
            'code' => '+228',
            'pattern' => '/^[0-9]+$/',
            'length' => 8,
        ],
        'GA' => [
            'code' => '+241',
            'pattern' => '/^[0-9]+$/',
            'length' => 8,
        ],
    ],
];
