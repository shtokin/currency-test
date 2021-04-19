<?php

namespace App\Service;

/**
 * Class CurrencyService
 * @package App\Service
 */
class CurrencyService
{

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * CurrencyService constructor.
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $base
     * @param string $quote
     * @return float
     * @throws \Exception
     */
    public function getCurrency(string $base, string $quote): float
    {
        $base = strtoupper($base);
        $quote = strtoupper($quote);

        try {
            $result = $this->getCurrencyInAttempts([$base, $quote]);
        } catch (\Exception $ex) {
            throw $ex;
        }

        // do this way instead sending base to exchangeratesapi.io because it's not supported by free subscription plan
        $rate = $result['rates'][$quote] / $result['rates'][$base];
        return round($rate, 2);
    }

    /**
     * @param array $symbols
     * @param int $attempts
     * @return array
     * @throws \Exception
     */
    private function getCurrencyInAttempts(array $symbols, int $attempts = 5): array
    {
        while ($attempts > 0) {
            try {
                return $this->apiService->getLatest($symbols);
            } catch (\Exception $ex) {
                $attempts++;
            }
            sleep(1);
        }

        throw new \Exception("Can\'t get latest currency in $attempts attempts for symbols: " . print_r($symbols, true));
    }
}