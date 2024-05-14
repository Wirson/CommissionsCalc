<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class CountryDataProvider
{
    private const array EU_COUNTRY_CODES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct(
        private readonly \App\Model\Contract\BinCountryCode $binCountryCode,
    ) {

    }

    public function isEuCountry(array $transactionData): bool
    {
        $countryCode = $this->binCountryCode->getBinCountryCode($transactionData['bin']);
        return in_array($countryCode, self::EU_COUNTRY_CODES);
    }
}
