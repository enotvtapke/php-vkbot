<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Entities\Event;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;

interface EventService
{
    /**
     * @return Event[]
     * @throws GuzzleException
     */
    public function findAll(): array;

    /**
     * @return Event[]
     * @throws GuzzleException
     */
    public function findAllWithStartBetween(DateTime $from, ?DateTime $to): array;
}
