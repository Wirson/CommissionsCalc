<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Tests\Unit\Model;

class RateProviderTest extends \PHPUnit\Framework\TestCase
{
    protected \Symfony\Contracts\HttpClient\HttpClientInterface $httpClientMock;
    protected \Symfony\Contracts\HttpClient\ResponseInterface $responseMock;
    protected \App\Model\BinLookupService $lookupService;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $this->responseMock = $this->createMock(\Symfony\Contracts\HttpClient\ResponseInterface::class);

        $this->lookupService = new \App\Model\BinLookupService($this->httpClientMock);
    }

    public function testGetBinCountryCode()
    {
        $binNumber = '123';
        $countryCode = 'PL';
        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with('GET', "https://lookup.binlist.net/$binNumber")
            ->willReturn($this->responseMock);

        $this->responseMock->method('toArray')->willReturn(['country' => ['alpha2' => $countryCode]]);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->assertSame($countryCode, $this->lookupService->getBinCountryCode($binNumber));
    }

    public function testGetBinCountryCodeFailure()
    {
        $binNumber = '123';
        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with('GET', "https://lookup.binlist.net/$binNumber")
            ->willReturn($this->responseMock);

        $this->responseMock->method('toArray')->willReturn([]);
        $this->responseMock->method('getStatusCode')->willReturn(123);
        $this->expectException(\Symfony\Component\HttpClient\Exception\TransportException::class);
        $this->lookupService->getBinCountryCode($binNumber);
    }
}