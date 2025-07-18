<?php

namespace netPhramework\db\abstraction\crud;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;

interface Delete
{
    public function where(Condition $condition): Delete;

    /**
     * @return bool
     * @throws MappingException
     */
    public function confirm():bool;
}