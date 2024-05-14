<?php
/**
 * @copyright Copyright (c) Mateusz Wira (m.wirson@gmail.com)
 */

declare(strict_types=1);

namespace App\Model;

class RateProvider implements \App\Model\Contract\ExchangeRates
{
    private array $rawRatesResult = [];

    public function __construct(
        private readonly \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient,
        private readonly string $accessToken,
        private readonly string $apilayerUrl,
    ) {

    }

    public function getCurrencyRate(string $currency): float
    {
        return (float) $this->getRates()['rates'][$currency];
    }

    private function getRates()
    {
        if (!$this->rawRatesResult) {
            $response = $this->httpClient->request(
                'GET',
                $this->apilayerUrl,
                ['headers' => ['apikey' => $this->accessToken]]
            );

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
