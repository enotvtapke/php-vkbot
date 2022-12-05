<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\EventService;
use App\Services\VkApiService;
use App\Utils\Config;
use Psr\Log\LoggerInterface;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;

class ServerHandler extends VKCallbackApiServerHandler
{
    private Config $config;
    private VkApiService $vkApiService;
    private EventService $eventService;
    private LoggerInterface $logger;

    public function __construct(
        Config          $config,
        VkApiService    $vkApiService,
        EventService    $eventService,
        LoggerInterface $logger
    )
    {
        $this->config = $config;
        $this->vkApiService = $vkApiService;
        $this->eventService = $eventService;
        $this->logger = $logger;
    }

    public function confirmation(int $group_id, ?string $secret)
    {
        $this->logger->info("Server confirmation requested from group $group_id");
        if (
            $secret !== $this->config->get('vk')['secret'] ||
            strval($group_id) !== $this->config->get('vk')['groupId']
        ) {
            $this->logger->info("Secret key or group id is invalid");
            return;
        }
        $this->logger->info("Sending confirmation response token");
        echo $this->config->get('vk')['confirmationToken'];
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $this->logger->info("New message from group $group_id: " . json_encode($object));
        if (
            $secret !== $this->config->get('vk')['secret'] ||
            strval($group_id) !== $this->config->get('vk')['groupId']
        ) {
            $this->logger->info("Secret key or group id is invalid");
            return;
        }
        $this->vkApiService->sendMessage(
            $object['message']->from_id,
            $object['message']->id,
            $this->eventService->findAll()
        );
    }
}
