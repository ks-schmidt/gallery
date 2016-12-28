<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Preview
{
    const SUFFIX = "pv";

    public static function convert(Image $image)
    {
        return $image->cropResize(1920);
    }
}
