<?php

if (! function_exists('gen_uuid')) {

    /**
     * Generates UUID with specific algorithm
     *
     * @param int $entityCode
     * @param int $appCode
     * @return string
     */
    function generate_uuid(int $entityCode = null, int $appCode = null)
    {
        return \Codiliateur\SmartUuid\Uuid::generateUuid($entityCode, $appCode);
    }
}

if (! function_exists('extract_uuid_part')) {

    /**
     * Decode timestamp from UUID
     *
     * @param string $uuid
     * @param string $part
     * @param string|null $format
     * @return mixed
     */
    function extract_uuid_part(string $uuid, string $part, string $format = 'Carbon')
    {
        return \Codiliateur\SmartUuid\Uuid::extractUuidPart($uuid, $part, $format);
    }
}

