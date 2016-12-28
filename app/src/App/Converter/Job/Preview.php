<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Preview
{
    public static function convert(Image $image)
    {
        return $image->cropResize(1920);
    }

    public static function getFile($fileInfo)
    {
        return sprintf(
            '%s.pv.%s', basename($fileInfo->getBasename('.' . $fileInfo->getExtension())), strtolower($fileInfo->getExtension())
        );
    }
}
