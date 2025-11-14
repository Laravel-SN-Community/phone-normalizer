<?php

namespace Laravelsn\PhoneNormalizer\Exceptions;

use InvalidArgumentException;

class InvalidPhoneNumberException extends InvalidArgumentException
{
    /**
     * @param  array<int, int>  $expectedLengths
     */
    public static function unexpectedLength(string $countryCode, int $actualLength, array $expectedLengths): self
    {
        $expected = implode(', ', $expectedLengths);

        return new self("Phone number for country [{$countryCode}] must have length(s): {$expected}; received length {$actualLength}.");
    }

    /**
     * @param  array<int, string>  $patterns
     */
    public static function patternMismatch(string $countryCode, string $phone, array $patterns): self
    {
        $expected = implode(', ', $patterns);

        return new self("Phone number [{$phone}] does not match any valid pattern for country [{$countryCode}]. Expected patterns: {$expected}.");
    }
}
