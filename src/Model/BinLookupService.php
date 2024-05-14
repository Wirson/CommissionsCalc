<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class BinLookupService implements \App\Model\Contract\BinCountryCode
{
    public function __construct(
        private readonly \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient,
    ) {

    }

    public function getBinCountryCode(string $binNumber): string
    {
        $binData = $this->getBinData($binNumber);
        return $binData['country']['alpha2'];
    }

    private function getBinData(string $binNumber): array
    {
        $response = $this->httpClient->request('GET', "https://lookup.binlist.net/$binNumber");
        $result = $response->toArray();
        if ($response->getStatusCode() !== 200) {
            throw new \Symfony\Component\HttpClient\Exception\TransportException('Bin not found');
        }
        return $result;
    }
}
