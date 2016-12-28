<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Download
{
    const SUFFIX = "dl";

    public static function convert(Image $image)
    {
        return $image;
    }
}
