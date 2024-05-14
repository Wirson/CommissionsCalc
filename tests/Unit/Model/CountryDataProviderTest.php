<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Tests\Unit\Model;

class CountryDataProviderTest extends \PHPUnit\Framework\TestCase
{
    protected \App\Model\Contract\BinCountryCode $binCountryCodeMock;
    protected \App\Model\CountryDataProvider $countryData;

    protected function setUp(): void
    {
        $this->binCountryCodeMock = $this->createMock(\App\Model\Contract\BinCountryCode::class);

        $this->countryData = new \App\Model\CountryDataProvider($this->binCountryCodeMock);
    }

    /**
     * @dataProvider isEuCountryDataProvider
     */
    public function testIsEuCountry(string $countryCode, bool $expected): void
    {
        $this->binCountryCodeMock->method('getBinCountryCode')->willReturn($countryCode);
        $this->assertSame($expected, $this->countryData->isEuCountry(['bin' => 'any']));
    }

    public function isEuCountryDataProvider(): array
    {
        return [
            ['ANY', false],
            ['AT', true],
            ['BE', true],
            ['BG', true],
            ['CY', true],
            ['CZ', true],
            ['DE', true],
            ['DK', true],
            ['EE', true],
            ['ES', true],
            ['FI', true],
            ['FR', true],
            ['GR', true],
            ['HR', true],
            ['HU', true],
            ['IE', true],
            ['IT', true],
            ['LT', true],
            ['LU', true],
            ['LV', true],
            ['MT', true],
            ['NL', true],
            ['PO', true],
            ['PT', true],
            ['RO', true],
            ['SE', true],
            ['SI', true],
            ['SK', true],
        ];
    }
}