<?php

namespace Codiliateur\SmartUuid;

use Carbon\Carbon;
use \RuntimeException;

/**
 * Class Uuid
 * @package Codiliateur\Uuid
 */
class Uuid {

    const TIMESTAMP = 'timestamp';
    const APP_CODE = 'app_code';
    const ENTITY_CODE = 'entity_code';

    const TS_FORMAT_CARBON = 1;
    const TS_FORMAT_DATETIME = 2;

    /**
     * @param int $entityCode
     * @param int $appCode
     * @return string
     */
    public static function generateUuid(int $entityCode = 0, int $appCode = 0): string
    {
        $mt = preg_split("/(\s|\.)/", microtime());

        $micro = str_pad(dechex(substr($mt[1], 1, 6)), 5, '0', STR_PAD_LEFT);

        return
            str_pad(dechex($mt[2]), 8, '0', STR_PAD_LEFT) . '-' .
            substr($micro, 0, 4) . '-' . substr($micro, -1) .
            str_pad(dechex($appCode), 3, '0', STR_PAD_LEFT) . '-' .
            str_pad(dechex($entityCode), 4, '0', STR_PAD_LEFT) . '-' .
            static::randomHex(6);
    }

    /**
     * @param string $uuid
     * @param string $part
     * @param string|int|null $format
     * @return mixed
     */
    public static function extractUuidPart(string $uuid, string $part, $format = null)
    {
        $part = strtolower($part);
        if ($part == static::TIMESTAMP) {
            return static::extractUuidTimestamp($uuid, $format);
        } elseif ($part == static::APP_CODE) {
            return static::extractUuidAppCode($uuid);
        } elseif ($part == static::ENTITY_CODE) {
            return static::extractUuidEntityCode($uuid);
        }

        throw new RuntimeException('extractUuidPart: undefined part');
    }

    /**
     * Generates random Hex-string
     *
     * @param int $bytes
     * @return string
     */
    protected static function randomHex(int $bytes = 4): string
    {
        $random = random_bytes(6);
        $random[0] = pack("C", ord($random[0]) | 1);

        return bin2hex($random);
    }

    /**
     * Extract timestamp part
     *
     * @param string $uuid
     * @param mixed $format
     * @return mixed
     */
    protected static function extractUuidTimestamp(string $uuid, $format = null)
    {
        $format = $format ?? self::TS_FORMAT_CARBON;

        $tz = date_default_timezone_get();

        $uuid = str_replace('-', '', $uuid);
        $ts = hexdec(substr($uuid, 0, 8)).'.'.substr(sprintf("%'.06d", hexdec(substr($uuid, 8, 5))), -6);

        if ($format == static::TS_FORMAT_DATETIME) {

            return \DateTime::createFromFormat('U.u', $ts)->setTimezone(new \DateTimeZone($tz));

        } elseif ($format == static::TS_FORMAT_CARBON) {

            return Carbon::createFromFormat('U.u', $ts)->timezone($tz);

        }

        return Carbon::createFromFormat('U.u', $ts)->timezone($tz)->format($format);
    }

    /**
     * Extract application code part
     *
     * @param string $uuid
     * @return int
     */
    protected static function extractUuidAppCode(string $uuid) : int
    {
        return (int) hexdec(substr($uuid, 15, 3));
    }

    /**
     * Extract entity code part
     *
     * @param string $uuid
     * @return int
     */
    protected static function extractUuidEntityCode(string $uuid) : int
    {
        return (int) hexdec(substr($uuid, 19, 4));
    }
}