<?php

namespace netPhramework\db\mysql;

use netPhramework\db\mapping\DataSet;

class TablesQuery implements Query
{
    public function getMySql(): string
    {
        return "SHOW TABLES";
    }

    public function getDataSet(): ?DataSet
    {
        return null;
    }
}