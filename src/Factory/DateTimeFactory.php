<?php

declare(strict_types=1);

namespace App\Factory;

class DateTimeFactory
{
    public static function createNowMutable(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('Europe/Berlin'));
    }

    public static function createNowImmutable(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin'));
    }
}