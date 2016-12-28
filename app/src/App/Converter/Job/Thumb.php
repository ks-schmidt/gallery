<?php

namespace Gallery\App\Converter\Job;

use Gregwar\Image\Image;

class Thumb
{
    const SUFFIX = "tn";

    public static function convert(Image $image)
    {
        return $image->zoomCrop(188, 188);
    }
}
