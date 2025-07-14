<?php

namespace netPhramework\db\mysql\queries;

use netPhramework\db\mapping\DataSet;
use netPhramework\db\mysql\Query;

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