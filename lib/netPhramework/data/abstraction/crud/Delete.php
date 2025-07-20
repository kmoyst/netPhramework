<?php

namespace netPhramework\data\abstraction\crud;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Condition;

interface Delete
{
    public function where(Condition $condition): Delete;

    /**
     * @return bool
     * @throws MappingException
     */
    public function confirm():bool;
}