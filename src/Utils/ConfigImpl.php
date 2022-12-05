<?php

declare(strict_types=1);

namespace App\Utils;

use InvalidArgumentException;

class ConfigImpl implements Config
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return mixed
     */
    public function get(string $key = '')
    {
        if ($this->settings[$key] === null) {
            throw new InvalidArgumentException("Configuration value for $key is null");
        }
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}
