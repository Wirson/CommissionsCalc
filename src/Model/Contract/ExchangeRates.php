<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

namespace App\Model\Contract;

interface ExchangeRates
{
    public function getCurrencyRate(string $currency): float;
}
