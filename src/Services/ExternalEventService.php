<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Entities\Event;
use App\Utils\Config;
use App\Utils\DateTimeUtils;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tebru\Gson\Gson;

class ExternalEventService implements EventService
{
    private Client $httpClient;
    private Config $config;
    private Gson $gson;

    public function __construct(Config $config, Gson $gson)
    {
        $this->config = $config;
        $this->httpClient = new Client([
            'base_uri' => $this->config->get('services')['eventServiceUrl'],
        ]);
        $this->gson = $gson;
    }

    /**
     * @return Event[]
     * @throws GuzzleException
     */
    public function findAll(): array
    {
        $response = $this->httpClient->request('GET', 'event');
        return $this->gson->fromJson($response->getBody()->getContents(), "array<\App\Domain\Entities\Event>");
    }

    /**
     * @return Event[]
     * @throws GuzzleException
     */
    public function findAllWithStartBetween(DateTime $from, ?DateTime $to): array
    {
        $query = [
            'from' => DateTimeUtils::toString($from),
            'to' => DateTimeUtils::toString($to)
        ];
        $response = $this->httpClient->request('GET', 'event/between', ['query' => $query]);
        return $this->gson->fromJson($response->getBody()->getContents(), "array<\App\Domain\Entities\Event>");
    }
}
