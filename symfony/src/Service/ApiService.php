<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiService
 * @package App\Service
 */
class ApiService
{

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $exchangeApiKey;

    /**
     *
     */
    const BASE_URL = 'http://api.exchangeratesapi.io/v1';

    /**
     * ApiService constructor.
     * @param HttpClientInterface $client
     * @param string $exchangeApiKey
     */
    public function __construct(HttpClientInterface $client, string $exchangeApiKey)
    {
        $this->client = $client;
        $this->exchangeApiKey = $exchangeApiKey;
    }

    /**
     * @param array $symbols
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getLatest(array $symbols): array
    {
        $url = self::BASE_URL . '/latest?access_key=' . $this->exchangeApiKey . '&symbols=' . implode(',', $symbols);

        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new \Exception('Exchangeratesapi code response: ' . $statusCode);
        }

        $result = $response->toArray();

        if (!$result['success']) {
            throw new \Exception('Cant get result from Exchangeratesapi. Response: ' . print_r($result, true));
        }

        return $result;
    }
}