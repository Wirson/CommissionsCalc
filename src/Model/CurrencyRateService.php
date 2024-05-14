<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class CurrencyRateService
{
    public function __construct(
        private readonly \App\Model\Contract\ExchangeRates $exchangeRates,
    ) {

    }
    public function getCurrencyRate(string $currency)
    {
        return $this->exchangeRates->getCurrencyRate($currency);
    }
}
