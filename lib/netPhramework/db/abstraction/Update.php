<?php

namespace netPhramework\db\abstraction;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\DataSet;

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