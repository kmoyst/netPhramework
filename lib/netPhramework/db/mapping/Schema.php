<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\MappingException;

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