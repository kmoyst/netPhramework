<?php

namespace netPhramework\db\mapping;

use netPhramework\common\Operator;
use netPhramework\db\exceptions\MappingException;

interface Delete
{
    public function where(string $key, string $value,
                          Operator $operator = Operator::EQUAL): Delete;

    /**
     * @return bool
     * @throws MappingException
     */
    public function confirm():bool;
}