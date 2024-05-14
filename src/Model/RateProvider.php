<?php

/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class RateProvider implements \App\Model\Contract\ExchangeRates
{
    private array $rawRatesResult = [];

    public function __construct(
        private readonly \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient,
        private readonly string $accessToken,
    ) {

    }

    public function getCurrencyRate(string $currency): float
    {
        return (float) $this->getRates()['rates'][$currency];
    }

    private function getRates()
    {
        if (!$this->rawRatesResult) {
            $response = $this->httpClient
                ->request('GET', 'https://api.exchangeratesapi.io/latest', ['headers' => ['access_key' => $this->accessToken]]);

            $result = $response->toArray();

            if ($response->getStatusCode() !== 200) {
                throw new \Symfony\Component\HttpClient\Exception\TransportException('Failed to get rates response');
            } elseif (isset($result['success']) && !$result['success'] && isset($result['error'])) {
                throw new \Symfony\Component\HttpClient\Exception\TransportException($result['error']['info']);
            }
            $this->rawRatesResult = $result;
        }

        return $this->rawRatesResult;
    }
}
