<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

namespace App\Model\Contract;

interface ExchangeRates
{
    public function getCurrencyRate(string $currency): float;
}
