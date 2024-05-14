<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Tests\Unit\Model;

class CurrencyRateServiceTest extends \PHPUnit\Framework\TestCase
{
    protected \App\Model\Contract\ExchangeRates $exchangeRatesMock;
    protected \App\Model\CurrencyRateService $currencyRateService;

    protected function setUp(): void
    {
        $this->exchangeRatesMock = $this->createMock(\App\Model\Contract\ExchangeRates::class);

        $this->currencyRateService = new \App\Model\CurrencyRateService($this->exchangeRatesMock);
    }

    public function testGetCurrencyRate(): void
    {
        $currency = 'any';
        $rate = 12.34;
        $this->exchangeRatesMock
            ->expects($this->once())
            ->method('getCurrencyRate')
            ->with($currency)
            ->willReturn($rate);
        $this->assertSame($rate, $this->currencyRateService->getCurrencyRate($currency));
    }
}