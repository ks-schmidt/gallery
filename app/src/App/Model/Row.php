<?php

namespace Gallery\App\Model;

use Gallery\App\Configuration;

abstract class Row
{
    protected $columns = [];
    protected $data = [];

    public function getColumns()
    {
        return $this->columns;
    }

}
