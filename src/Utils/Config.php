<?php

declare(strict_types=1);

namespace App\Utils;

interface Config
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');
}
