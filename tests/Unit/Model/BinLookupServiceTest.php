<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Tests\Unit\Model;

class BinLookupServiceTest extends \PHPUnit\Framework\TestCase
{
    private const CURRENCY = 'USD';
    protected \Symfony\Contracts\HttpClient\ResponseInterface $responseMock;
    protected \App\Model\RateProvider $rateProvider;

    protected function setUp(): void
    {
        $this->responseMock = $this->createMock(\Symfony\Contracts\HttpClient\ResponseInterface::class);

        $accessToken = 'access_token';
        $httpClientMock = $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api.exchangeratesapi.io/latest',
                ['headers' => ['access_key' => $accessToken]]
            )->willReturn($this->responseMock);

        $this->rateProvider = new \App\Model\RateProvider($httpClientMock, $accessToken);
    }

    public function testGetCurrencyRate()
    {
        $expectedRate = 12.34;

        $this->responseMock->method('toArray')->willReturn(['rates' => [self::CURRENCY => $expectedRate]]);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->assertSame($expectedRate, $this->rateProvider->getCurrencyRate(self::CURRENCY));
    }

    public function testGetBinCountryCodeFailure()
    {
        $this->responseMock->method('toArray')->willReturn([]);
        $this->responseMock->method('getStatusCode')->willReturn(123);
        $this->expectException(\Symfony\Component\HttpClient\Exception\TransportException::class);
        $this->rateProvider->getCurrencyRate(self::CURRENCY);
    }

    public function testGetBinCountryCodeApiError()
    {
        $this->responseMock->method('toArray')->willReturn([
            'success' => false,
            'error' => [
                'info' => 'any'
            ]
        ]);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->expectException(\Symfony\Component\HttpClient\Exception\TransportException::class);
        $this->rateProvider->getCurrencyRate(self::CURRENCY);
    }

}