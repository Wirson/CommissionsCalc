<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

namespace App\Model\Contract;

interface BinCountryCode
{
    public function getBinCountryCode(string $binNumber): string;
}
