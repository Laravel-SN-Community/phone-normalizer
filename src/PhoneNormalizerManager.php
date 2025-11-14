<?php

namespace Laravelsn\PhoneNormalizer;

use Illuminate\Support\Facades\Config;
use Laravelsn\PhoneNormalizer\Exceptions\InvalidPhoneNumberException;

class PhoneNormalizerManager
{
    /**
     * Normalize a phone number for a specific country
     */
    public function normalize(string $phone, ?string $countryCode = null): string
    {
        $countryCode = $countryCode ?? Config::get('phonenormalizer.default_country', 'SN');

        $countryConfig = Config::get("phonenormalizer.countries.{$countryCode}");

        $validatedConfig = $this->validateCountryConfig($countryCode, $countryConfig);

        $cleanedPhone = preg_replace('/[^0-9]/', '', $phone) ?? '';

        $nationalNumber = $this->stripCountryCallingCode(
            $cleanedPhone,
            $validatedConfig['code'],
            $validatedConfig['lengths']
        );

        $phoneLength = strlen($nationalNumber);

        if (! $this->isExpectedLength($nationalNumber, $validatedConfig['lengths'])) {
            throw InvalidPhoneNumberException::unexpectedLength($countryCode, $phoneLength, $validatedConfig['lengths']);
        }

        if (! $this->matchesAnyPattern($nationalNumber, $validatedConfig['patterns'])) {
            throw InvalidPhoneNumberException::patternMismatch($countryCode, $nationalNumber, $validatedConfig['patterns']);
        }

        return $validatedConfig['code'].$nationalNumber;
    }

    /**
     * @param  mixed  $countryConfig
     *
     * @return array{code:string,lengths:array<int,int>,patterns:array<int,string>}
     */
    private function validateCountryConfig(string $countryCode, $countryConfig): array
    {
        if (! is_array($countryConfig) || $countryConfig === []) {
            throw new \InvalidArgumentException("Country [{$countryCode}] is not configured.");
        }

        $code = $countryConfig['code'] ?? null;
        if (! is_string($code) || $code === '') {
            throw new \InvalidArgumentException("Country [{$countryCode}] configuration must define a non-empty string code.");
        }

        $lengths = $countryConfig['length'] ?? null;
        if (is_int($lengths)) {
            $lengths = [$lengths];
        }

        if (! is_array($lengths) || $lengths === []) {
            throw new \InvalidArgumentException("Country [{$countryCode}] configuration must define a length or array of lengths.");
        }

        $lengths = array_values(array_filter($lengths, static function ($length) {
            return is_int($length) && $length > 0;
        }));

        if ($lengths === []) {
            throw new \InvalidArgumentException("Country [{$countryCode}] configuration contains invalid length values.");
        }

        $patterns = $countryConfig['pattern'] ?? null;
        if (is_string($patterns)) {
            $patterns = [$patterns];
        }

        if (! is_array($patterns) || $patterns === []) {
            throw new \InvalidArgumentException("Country [{$countryCode}] configuration must define a pattern or array of patterns.");
        }

        $patterns = array_values(array_filter($patterns, static function ($pattern) {
            return is_string($pattern) && $pattern !== '';
        }));

        if ($patterns === []) {
            throw new \InvalidArgumentException("Country [{$countryCode}] configuration contains invalid pattern values.");
        }

        return [
            'code' => $code,
            'lengths' => $lengths,
            'patterns' => $patterns,
        ];
    }

    /**
     * @param  array<int, int>  $expectedLengths
     */
    private function isExpectedLength(string $phone, array $expectedLengths): bool
    {
        return in_array(strlen($phone), $expectedLengths, true);
    }

    /**
     * @param  array<int, string>  $patterns
     */
    private function matchesAnyPattern(string $phone, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $phone) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, int>  $expectedLengths
     */
    private function stripCountryCallingCode(string $phone, string $countryCode, array $expectedLengths): string
    {
        $numericCode = preg_replace('/[^0-9]/', '', $countryCode) ?? '';

        if ($numericCode === '') {
            return $phone;
        }

        $codeLength = strlen($numericCode);

        foreach ($expectedLengths as $expectedLength) {
            if (
                strlen($phone) === $codeLength + $expectedLength
                && strncmp($phone, $numericCode, $codeLength) === 0
            ) {
                return substr($phone, $codeLength);
            }
        }

        return $phone;
    }
}
