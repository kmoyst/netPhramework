<?php

namespace netPhramework\data\mysql\queries;

use netPhramework\data\mapping\DataSet;
use netPhramework\data\mysql\Query;

class ShowTables implements Query
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