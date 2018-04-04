<?php

namespace webignition\NodeJslint\Wrapper;

class FileUrlDetector
{
    const FILE_URL_PREFIX = 'file:';

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function isFileUrl($url)
    {
        return substr($url, 0, strlen(self::FILE_URL_PREFIX)) == self::FILE_URL_PREFIX;
    }
}
