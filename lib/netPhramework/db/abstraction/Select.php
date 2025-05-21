<?php

namespace netPhramework\db\abstraction;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;

interface Select
{
    public function where(Condition $condition):Select;
    /**
     * @return array
     * @throws MappingException
     */
    public function getData():array;
}