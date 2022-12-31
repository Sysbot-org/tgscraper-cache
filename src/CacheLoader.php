<?php

namespace TgScraper\Cache;

use OutOfBoundsException;

/**
 * Class CacheLoader
 * @package TgScraper\Cache
 */
class CacheLoader
{

    /**
     * Path to the webpages.
     */
    protected const ASSETS_PATH = __DIR__ . '/../assets';

    /**
     * @var string[] $versions
     */
    protected static array $versions;

    /**
     * CacheLoader constructor.
     */
    public function __construct()
    {
        if (empty(self::$versions)) {
            $items = array_diff(scandir(self::ASSETS_PATH), ['.', '..']);
            foreach ($items as $item) {
                self::$versions[pathinfo($item, PATHINFO_FILENAME)] = realpath(self::ASSETS_PATH . '/' . $item);
            }
        }
    }

    /**
     * @return string[]
     */
    public function listVersions(): array
    {
        return array_keys(self::$versions);
    }

    /**
     * @return string
     */
    public function getStableVersion(): string
    {
        return end(self::$versions);
    }

    /**
     * @param string $version
     * @return string
     */
    public function getVersion(string $version): string
    {
        $version = self::parseVersionNumber($version);
        $result = self::$versions[$version] ?? null;
        if(empty($result)) {
            throw new OutOfBoundsException('Specified version not found');
        }
        return $result;
    }

    /**
     * @deprecated
     * @param string $version
     * @return string
     */
    public static function getCachedVersion(string $version): string
    {
        $version = self::parseVersionNumber($version);
        $filename = sprintf('%s/../assets/%s.html', __DIR__, $version);
        if (!file_exists($filename)) {
            throw new OutOfBoundsException('Specified version not found');
        }
        return realpath($filename);
    }

    /**
     * @param string $version
     * @return string
     */
    private static function parseVersionNumber(string $version): string
    {
        return 'v' . str_replace(['.', 'v'], ['', ''], strtolower($version));
    }

}