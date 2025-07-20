<?php

namespace netPhramework\data\abstraction\crud;

use netPhramework\data\exceptions\DuplicateEntryException;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Condition;
use netPhramework\data\mapping\DataSet;

interface Update
{
    public function withData(DataSet $dataSet): Update;

    public function where(Condition $condition):Update;
    /**
     * @return bool
     * @throws MappingException
     * @throws DuplicateEntryException
     */
    public function confirm():bool;
}