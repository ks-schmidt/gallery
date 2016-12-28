<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Thumb
{
    public static function convert(Image $image)
    {
        return $image->zoomCrop(188, 188);
    }

    public static function getFile($fileInfo)
    {
        return sprintf(
            '%s.tn.%s', basename($fileInfo->getBasename('.' . $fileInfo->getExtension())), strtolower($fileInfo->getExtension())
        );
    }
}
