<?php

namespace netPhramework\db\abstraction;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Field;
use netPhramework\db\mapping\FieldSet;

interface Schema
{
	/**
	 * @return FieldSet
	 * @throws MappingException
	 */
	public function getFieldSet():FieldSet;

	/**
	 * @return Field
	 * @throws MappingException
	 */
	public function getPrimary():Field;
}