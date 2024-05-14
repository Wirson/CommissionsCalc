<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

namespace App\Model\Contract;

interface BinCountryCode
{
    public function getBinCountryCode(string $binNumber): string;
}
