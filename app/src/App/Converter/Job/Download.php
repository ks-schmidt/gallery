<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Download
{
    public static function convert(Image $image)
    {
        return $image->cropResize(2560);
    }

    public static function getFile($fileInfo)
    {
        return sprintf(
            '%s.dl.%s', basename($fileInfo->getBasename('.' . $fileInfo->getExtension())), strtolower($fileInfo->getExtension())
        );
    }
}
