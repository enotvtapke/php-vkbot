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

    public function sendMessage(int $userId, string $message): void
    {
        $this->logger->info("Sending message to user $userId: $message");
        $this->vkApi->messages()->send(
            $this->config->get('vk')['accessToken'],
            ['peer_id' => $userId, 'user_id' => $userId, 'random_id' => mt_rand(), 'message' => $message]
        );
    }

    public function sendKeyboard(int $userId, string $message, string $keyboardName): void
    {
        $this->logger->info("Sending keyboard '$keyboardName' to user $userId with message: $message");
        $keyboard = file_get_contents(RESOURCES_ROOT . "keyboards/$keyboardName.json");
        $this->logger->debug("Keyboard was read from file: $keyboard");
        $this->vkApi->messages()->send(
            $this->config->get('vk')['accessToken'],
            ['peer_id' => $userId,
                'user_id' => $userId,
                'random_id' => mt_rand(),
                'message' => $message,
                'keyboard' => $keyboard,
            ]
        );
    }
}
