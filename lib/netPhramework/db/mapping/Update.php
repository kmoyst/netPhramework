<?php

namespace netPhramework\db\mapping;

use netPhramework\common\Operator;
use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\MappingException;

interface Update
{
    public function withData(array $rowData): Update;

    public function where(string $key, string $value,
                          Operator $operator = Operator::EQUAL):Update;
    /**
     * @return bool
     * @throws MappingException
     * @throws DuplicateEntryException
     */
    public function confirm():bool;
}