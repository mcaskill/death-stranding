<?php

declare(strict_types=1);

namespace Bridges;

use DateTimeImmutable;

class App
{
    public const VERSION     = '2020-11-22T05:20:00UTC';
    public const SITE_NAME   = 'Bridges';
    public const DESCRIPTION = 'A list of standard orders, facilities, and related activities, in Death Stranding.';
    public const QUOTE       = 'Keep on Keepin’ On, Porter!';

    public static function getVersionAsDateTimeString() : string
    {
        $datetime = new DateTimeImmutable(static::VERSION);

        return $datetime->format('Y-m-d H:i T');
    }

    public static function formatDocumentTitle(string $pageTitle = null) : string
    {
        return static::SITE_NAME . ' / ' . $pageTitle;
    }
}
