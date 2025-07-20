<?php

namespace netPhramework\data\abstraction\crud;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Condition;

interface Select
{
    public function where(Condition $condition):Select;
    /**
     * @return array
     * @throws MappingException
     */
    public function getData():array;
}