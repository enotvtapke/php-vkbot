<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;

interface EventService
{
    /**
     * @throws GuzzleException
     */
    public function findAll(): string;
}
