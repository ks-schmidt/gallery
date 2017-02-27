<?php

namespace Gallery\App\Model\Img\File;

use Gallery\App\Model\Table as AbstractTable;

class Table extends AbstractTable
{
    CONST TABLE = 'img_table';

    protected $primary = 'id_img_file';
    protected $row = \Gallery\App\Model\Img\File\Row::class;
}
