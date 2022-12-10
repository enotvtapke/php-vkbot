<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\EventService;
use App\Services\VkApiService;
use App\Utils\Config;
use App\Views\EventsView;
use DateInterval;
use DateTime;
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
        if (isset($object['message']->payload)) {
            $this->vkApiService->sendMessage(
                $object['message']->from_id,
                $this->getAnswer(json_decode($object['message']->payload)) ?: 'Empty message',
            );
        } else {
            $this->vkApiService->sendKeyboard(
                $object['message']->from_id,
                'Please, use keyboard to send commands',
                'default'
            );
        }
    }

    public function getAnswer(object $payload): string
    {
        $this->logger->info("Generating answer based on payload: " . json_encode($payload));
        switch ($payload->domain) {
            case 'event':
                switch ($payload->method) {
                    case 'findAll':
                        $message = (new EventsView($this->eventService->findAll()))->render();
                        break;
                    case 'findInNextDays':
                        $days = (int)$payload->args->days;
                        $until = (new DateTime())->add(new DateInterval("P{$days}D"));
                        $message =
                            (new EventsView(
                                $this->eventService->findAllWithStartBetween(new DateTime(), $until))
                            )->render();
                        break;
                    default:
                        $this->logger->warning(
                            "Invalid event method {$payload->method} in message payload $payload"
                        );
                        $message = "Invalid event method";
                }
                break;
            default:
                $this->logger->warning(
                    "Invalid domain {$payload->domain} in message payload $payload"
                );
                $message = "Invalid payload domain";
        }
        return $message;
    }

    public function parse($event)
    {
        if ($event->type == static::CALLBACK_EVENT_CONFIRMATION) {
            $this->confirmation($event->group_id, $event->secret);
        } else {
            $group_id = $event->group_id;
            $secret = $event->secret;
            $type = $event->type;
            $object = (array)$event->object;
            $this->logger->info("Received message of type $type from group $group_id: " . json_encode($object));
            if (
                $secret !== $this->config->get('vk')['secret'] ||
                strval($group_id) !== $this->config->get('vk')['groupId']
            ) {
                $this->logger->info("Secret key or group id is invalid");
                return;
            }
            $this->parseObject($group_id, $secret, $type, $object);
        }
    }
}
