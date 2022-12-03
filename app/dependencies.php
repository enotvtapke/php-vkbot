<?php

declare(strict_types=1);

use App\Controllers\ServerHandler;
use App\Services\EventService;
use App\Services\ExternalEventService;
use App\Services\VkApiService;
use App\Utils\Config;
use App\Utils\ConfigImpl;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use VK\Client\VKApiClient;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Config::class => fn() => new ConfigImpl([
            // TODO env vars
            'vk' => [
                'accessToken' => 'vk1.a.8sMVP3UXqgkMguLZeOB96V7PTwnO5JxCCYT85YiGWP4LFHzoxTAPQPhLKxkbBS9rBjvFaBKJxhJBuVroNHTs936dDqRelk_JmpRaDC73LaVFiwDpFcQ5IVGbA0mJVYTvzAxesj_3DV8RL97ADKX9ltKlcpcrhiJl8oebyhUi38bryAlmhpi3VYGLT0S91C7dDxlZ2DgQnNXxaj2DGBanRA',
                'groupId' => 217440701,
                'confirmationToken' => '5c7fc142',
                'secret' => 'FMBug0XtsupZ',
            ],
            'services' => [
                'eventServiceUrl' => (getenv('EVENT_SERVICE_URL') ?: 'localhost:8100') . '/api/v1/',
            ]
        ]),
        VKApiClient::class => fn() => new VKApiClient(),
        VkApiService::class => fn(ContainerInterface $c) => new VkApiService(
            $c->get(Config::class),
            $c->get(VKApiClient::class),
        ),
        EventService::class => fn(ContainerInterface $c) => new ExternalEventService(
            $c->get(Config::class)
        ),
        ServerHandler::class => fn(ContainerInterface $c) => new ServerHandler(
            $c->get(Config::class),
            $c->get(VkApiService::class),
            $c->get(EventService::class),
        ),
    ]);
};
