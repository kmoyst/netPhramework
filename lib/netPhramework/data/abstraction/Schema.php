<?php

namespace netPhramework\data\abstraction;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Field;
use netPhramework\data\mapping\FieldSet;

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