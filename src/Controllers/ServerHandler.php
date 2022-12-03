<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\EventService;
use App\Services\VkApiService;
use App\Utils\Config;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;

class ServerHandler extends VKCallbackApiServerHandler
{
    private Config $config;
    private VkApiService $vkApiService;
    private EventService $eventService;

    public function __construct(Config $config, VkApiService $vkApiService, EventService $eventService)
    {
        $this->config = $config;
        $this->vkApiService = $vkApiService;
        $this->eventService = $eventService;
    }

    public function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === $this->config->get('vk')['secret'] && $group_id === $this->config->get('vk')['groupId']) {
            echo $this->config->get('vk')['confirmationToken'];
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        echo $this->eventService->findAll();
        $this->vkApiService->sendMessage($object['message']->user_id, $object['message']->text);
    }
}
