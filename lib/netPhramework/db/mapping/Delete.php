<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\MappingException;

interface Delete
{
    public function where(Condition $condition): Delete;

    /**
     * @return bool
     * @throws MappingException
     */
    public function confirm():bool;
}