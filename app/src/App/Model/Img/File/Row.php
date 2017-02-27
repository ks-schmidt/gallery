<?php

namespace Gallery\App\Model\Img\File;

use Gallery\App\Model\Row as AbstractRow;

class Row extends AbstractRow
{
    protected $columns = [
        "id_file", "fk_folder", "path", "name", "extension", "size", "description", "tag",
    ];
}
