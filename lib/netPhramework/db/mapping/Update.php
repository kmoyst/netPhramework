<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MappingException;

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