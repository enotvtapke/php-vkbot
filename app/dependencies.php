<?php

declare(strict_types=1);

use App\Controllers\ServerHandler;
use App\Services\EventService;
use App\Services\ExternalEventService;
use App\Services\VkApiService;
use App\Utils\Config;
use App\Utils\ConfigImpl;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tebru\Gson\Gson;
use VK\Client\VKApiClient;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Config::class => fn() => new ConfigImpl([
            'vk' => [
                'accessToken' => getenv('VK_ACCESS_TOKEN') ?: null,
                'groupId' => getenv('VK_GROUP_ID') ?: null,
                'confirmationToken' => getenv('VK_CONFIRMATION_TOKEN') ?: null,
                'secret' => getenv('VK_SECRET') ?: null,
            ],
            'services' => [
                'eventServiceUrl' => (getenv('EVENT_SERVICE_URL') ?: 'localhost:8100') . '/api/v1/',
            ],
            'logger' => [
                'name' => 'calendar-vkbot',
                'path' => 'php://stdout',
                'level' => Logger::DEBUG,
            ],
        ]),
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(Config::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        VKApiClient::class => fn() => new VKApiClient(),
        VkApiService::class => fn(ContainerInterface $c) => new VkApiService(
            $c->get(Config::class),
            $c->get(VKApiClient::class),
            $c->get(LoggerInterface::class),
        ),
        Gson::class => fn(ContainerInterface $c) => Gson::builder()->build(),
        EventService::class => fn(ContainerInterface $c) => new ExternalEventService(
            $c->get(Config::class),
            $c->get(Gson::class),
        ),
        ServerHandler::class => fn(ContainerInterface $c) => new ServerHandler(
            $c->get(Config::class),
            $c->get(VkApiService::class),
            $c->get(EventService::class),
            $c->get(LoggerInterface::class),
        ),
    ]);
};
