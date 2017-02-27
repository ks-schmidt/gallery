<?php

namespace Gallery\App\Model;

abstract class Table
{
    protected $primary;
    protected $row;

    public function getPrimaryKey()
    {
        return $this->primary;
    }

    public function getRowClassname()
    {
        return $this->row;
    }



    public function createOrGetRowFromData(array $data = [])
    {
        $row = $this->getRowClassname()->getColumns();
        if (isset($data[$this->getRowClassname()->getPrimaryKey()])) {

        }

        foreach ($row as $column => & $value) {
            $value = null;
            if (array_key_exists($column, $data)) {
                $value = $data[$column];
            }
        }

        return $row;
    }

    public function createRow(array $data = [])
    {

    }

    public function findByPrimary($value)
    {

    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getAdapter()
    {
        return Configuration::get('db_adapter');
    }
}
