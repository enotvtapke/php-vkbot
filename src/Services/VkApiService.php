<?php

declare(strict_types=1);

namespace App\Services;

use App\Utils\Config;
use Psr\Log\LoggerInterface;
use VK\Client\VKApiClient;

class VkApiService
{
    private Config $config;
    private VKApiClient $vkApi;
    private LoggerInterface $logger;

    public function __construct(Config $config, VKApiClient $vkApi, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->vkApi = $vkApi;
        $this->logger = $logger;
    }

    public function sendMessage(int $userId, int $randomId, string $message): void
    {
        $this->logger->info("Sending message to user $userId: $message");
        $this->vkApi->messages()->send(
            $this->config->get('vk')['accessToken'],
            ['peer_id' => $userId, 'user_id' => $userId, 'random_id' => $randomId, 'message' => $message]
        );
    }
}
