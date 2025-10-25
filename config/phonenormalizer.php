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
    ],
];
