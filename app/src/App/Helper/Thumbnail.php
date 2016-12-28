<?php

namespace Gallery\App\Helper;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Thumbnail
{
    public static function getFirstOfPath($path)
    {
        $it = new \RegexIterator(new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST
            ), '/.*\.tn\.jpe?g$/i', \RegexIterator::MATCH
        );

        foreach ($it as $fileInfo) {
            return $fileInfo->getRealPath();
        }
    }
}
