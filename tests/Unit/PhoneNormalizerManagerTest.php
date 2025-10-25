<?php

use Laravelsn\PhoneNormalizer\Facades\PhoneNormalizer as Phone;

it('normalizes senegalese phone number with spaces', function () {
    $result = Phone::normalize('78 485 28 12');

    expect($result)->toBe('+221784852812');
});
