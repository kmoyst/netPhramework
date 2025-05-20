<?php

namespace netPhramework\db\mapping;

use netPhramework\db\core\FieldSet;
use netPhramework\db\exceptions\MappingException;

interface Schema
{
	/**
	 * @return FieldSet
	 * @throws MappingException
	 */
	public function getFieldSet():FieldSet;

	/**
	 * @return string
	 * @throws MappingException
	 */
	public function getIdKey():string;
}