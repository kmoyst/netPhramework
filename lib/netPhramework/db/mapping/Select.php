<?php

namespace netPhramework\db\mapping;

use netPhramework\common\Operator;
use netPhramework\db\exceptions\MappingException;

interface Select
{
    public function setFieldNames(array $names): Select;
    public function where(string $key,
                          string $value,
                          Operator $operator = Operator::EQUAL):Select;
    /**
     * @return array
     * @throws MappingException
     */
    public function getData():array;
}