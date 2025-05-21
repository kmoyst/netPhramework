<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\MappingException;

interface Select
{
    public function where(Condition $condition):Select;
    /**
     * @return array
     * @throws MappingException
     */
    public function getData():array;
}