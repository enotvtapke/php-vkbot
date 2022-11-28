<?php

declare(strict_types=1);

namespace App\Services;

use App\Utils\Config;
use VK\Client\VKApiClient;

class VkApiService
{
    private Config $config;
    private VKApiClient $vkApi;

    public function __construct(Config $config, VKApiClient $vkApi)
    {
        $this->config = $config;
        $this->vkApi = $vkApi;
    }

    public function sendMessage(int $userId, string $message): void
    {
        $this->vkApi->messages()->send(
            $this->config->get('accessToken'),
            ['peer_id' => $userId, 'user_id' => $userId, 'random_id' => mt_rand(), 'message' => $message]
        );
    }
}
