<?php

declare(strict_types=1);

namespace App\Utils;

use DateTime;
use DateTimeInterface;

class DateTimeUtils
{
    private static string $format = DateTimeInterface::ATOM;

    public static function toString(DateTime $dateTime): string
    {
        return $dateTime->format(DateTimeUtils::$format);
    }

    public static function fromString(string $s): DateTime
    {
        return new DateTime($s);
    }
}