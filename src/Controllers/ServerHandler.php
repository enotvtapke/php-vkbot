<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\VkApiService;
use App\Utils\Config;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;

class ServerHandler extends VKCallbackApiServerHandler
{
    private Config $config;
    private VkApiService $vkApiService;

    public function __construct(Config $config, VkApiService $vkApiService)
    {
        $this->config = $config;
        $this->vkApiService = $vkApiService;
    }

    public function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === $this->config->get('secret') && $group_id === $this->config->get('groupId')) {
            echo $this->config->get('confirmationToken');
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $this->vkApiService->sendMessage($object['message']->user_id, $object['message']->text);
        echo 'ok';
    }
}
