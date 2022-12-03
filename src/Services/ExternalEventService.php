<?php

declare(strict_types=1);

namespace App\Services;

use App\Utils\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExternalEventService implements EventService
{
    private Client $httpClient;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->httpClient = new Client([
            'base_uri' => $this->config->get('services')['eventServiceUrl'],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function findAll(): string
    {
        $res = $this->httpClient->request('GET', 'event');
        return $res->getBody()->getContents();
    }
}