<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Tests\Unit\Model;

class CommissionCalculatorTest extends \PHPUnit\Framework\TestCase
{
    protected \App\Model\CountryDataProvider $countryDataMock;
    protected \App\Model\CurrencyRateService $currencyRateMock;
    protected \App\Model\CommissionCalculator $commissionCalculator;

    protected function setUp(): void
    {
        $this->countryDataMock = $this->createMock(\App\Model\CountryDataProvider::class);
        $this->currencyRateMock = $this->createMock(\App\Model\CurrencyRateService::class);

        $this->commissionCalculator = new \App\Model\CommissionCalculator(
            $this->countryDataMock,
            $this->currencyRateMock
        );
    }

    /**
     * @dataProvider calculateDataProvider
     */
    public function testCalculate(string $currency, bool $isEu, float $amount, float $rate, float $expected): void
    {
        $this->countryDataMock->method('isEuCountry')->willReturn($isEu);
        $this->currencyRateMock->method('getCurrencyRate')->willReturn($rate);
        $this->assertSame($expected, $this->commissionCalculator->calculate(['currency' => $currency, 'amount' => $amount]));
    }

    public function calculateDataProvider(): array
    {
        return [
            ['EUR', true, 100.0, 0.1, 10.0],
            ['EUR', true, 100.0, 0, 1.0],
            ['ANY', true, 100.0, 0.1, 10.0],
            ['ANY', true, 100.0, 0.23, 4.35],
            ['EUR', false, 100.0, 0.111, 18.02],
            ['EUR', false, 100.0, 0, 2.0],
            ['ANY', false, 100.0, 0.123, 16.26],
            ['ANY', false, 100.0, 0.23, 8.7],
        ];
    }
}