<?php

namespace Gallery\App\Model\Img\Folder;

use Gallery\App\Model\Row as AbstractRow;

class Row extends AbstractRow
{
    protected $columns = [
        "id_folder", "path",
    ];
}
