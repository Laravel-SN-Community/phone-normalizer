<?php

use InvalidArgumentException;
use Laravelsn\PhoneNormalizer\Exceptions\InvalidPhoneNumberException;
use Laravelsn\PhoneNormalizer\Facades\PhoneNormalizer as Phone;

it('normalizes senegalese phone number with spaces', function () {
    $result = Phone::normalize('78 485 28 12');

    expect($result)->toBe('+221784852812');
});

it('normalizes senegalese phone number that already includes the country code', function () {
    $result = Phone::normalize('221 78 485 28 12');

    expect($result)->toBe('+221784852812');
});

it('normalizes senegalese phone number that already includes the country code and not spaces', function () {
    $result = Phone::normalize('221784852812');

    expect($result)->toBe('+221784852812');
});

it('accepts multiple lengths for the same country', function () {
    config()->set('phonenormalizer.countries.SN.length', [8, 9]);

    $result = Phone::normalize('784852812');

    expect($result)->toBe('+221784852812');
});

it('accepts multiple patterns for the same country', function () {
    config()->set('phonenormalizer.countries.SN.pattern', [
        '/^(7[05678][0-9]{7})$/',
        '/^(338[0-9]{6})$/',
    ]);

    $result = Phone::normalize('338123456');

    expect($result)->toBe('+221338123456');
});

it('throws when the country configuration is invalid', function () {
    config()->set('phonenormalizer.countries.SN', []);

    Phone::normalize('784852812');
})->throws(InvalidArgumentException::class, 'Country [SN] is not configured.');

it('throws when the phone number has an unexpected length', function () {
    Phone::normalize('78485281');
})->throws(InvalidPhoneNumberException::class, 'Phone number for country [SN] must have length(s): 9; received length 8.');

it('throws when the phone number does not match the expected pattern', function () {
    Phone::normalize('731234567');
})->throws(InvalidPhoneNumberException::class, 'Phone number [731234567] does not match any valid pattern for country [SN]. Expected patterns: /^(7[05678][0-9]{7})$/.');
