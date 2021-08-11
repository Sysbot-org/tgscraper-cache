<?php

namespace TgScraper\Cache;

use OutOfBoundsException;

class CacheLoader
{

    public static function getCachedVersion(string $version): string
    {
        $version = self::parseVersionNumber($version);
        $filename = sprintf('../assets/%s.html', $version);
        if (!file_exists($filename)) {
            throw new OutOfBoundsException('Specified version not found');
        }
        return realpath($filename);
    }

    private static function parseVersionNumber(string $version): string
    {
        return 'v' . str_replace(['.', 'v'], ['', ''], strtolower($version));
    }

}