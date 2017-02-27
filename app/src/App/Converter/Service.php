<?php

namespace Gallery\App\Converter;

use Arara\Process\Child;
use Arara\Process\Control;
use Arara\Process\Pool;

use Gallery\App\Configuration;

use Gallery\App\Model\Img\File\Table as DBImgFileTable;
use Gallery\App\Model\Img\File\Row as DBImgFileRow;

class Service
{
    public function read()
    {
        $path = Configuration::get('settings.converter.source.path');
        $filter = Configuration::get('settings.converter.source.filter');

        $it = new FileIterator($path, $filter);
        foreach ($it as $file) {

        }

        $dbTable = new DBImgFileTable();

    }

    public function convert()
    {
        // convert
        // ... images from database using fork for each processor
    }
}
