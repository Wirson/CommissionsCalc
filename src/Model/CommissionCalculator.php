<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class CommissionCalculator
{
    public function __construct(
        private readonly \App\Model\CountryDataProvider $countryDataProvider,
        private readonly \App\Model\CurrencyRateService $currencyRateService,
    ) {

    }

    public function calculate(array $transactionData): float
    {
        $isEu = $this->countryDataProvider->isEuCountry($transactionData);

        $rate = $this->currencyRateService->getCurrencyRate($transactionData['currency']);

        if ($transactionData['currency'] == 'EUR' || $rate == 0) {
            $amntFixed = $transactionData['amount'];
        }
        if ($transactionData['currency'] != 'EUR' || $rate > 0) {
            $amntFixed = $transactionData['amount'] / $rate;
        }

        return round($amntFixed * ($isEu ? 0.01 : 0.02), 2);
    }
}
