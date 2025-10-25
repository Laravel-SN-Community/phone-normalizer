<?php

namespace Laravelsn\PhoneNormalizer;

use Illuminate\Support\Facades\Config;

class PhoneNormalizerManager
{
    /**
     * Normalize a phone number for a specific country
     */
    public function normalize(string $phone, ?string $countryCode = null): ?string
    {
        $countryCode = $countryCode ?? Config::get('phonenormalizer.default_country', 'SN');

        $countryConfig = Config::get("phonenormalizer.countries.{$countryCode}");

        if (! $countryConfig) {
            return null;
        }

        // Clean the phone number (remove all non-numeric characters)
        $cleanedPhone = preg_replace('/[^0-9]/', '', $phone);

        // Validate the phone number length
        if (strlen($cleanedPhone) !== $countryConfig['length']) {
            return null;
        }

        // Validate the phone number pattern
        if (! preg_match($countryConfig['pattern'], $cleanedPhone)) {
            return null;
        }

        // Return the normalized phone number with country code
        return $countryConfig['code'].$cleanedPhone;
    }
}
