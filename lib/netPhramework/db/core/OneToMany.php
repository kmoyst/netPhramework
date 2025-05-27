<?php

namespace netPhramework\db\core;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;

interface OneToMany
{
	/**
	 * @param Record $record
	 * @return RecordSet
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getChildren(Record $record):RecordSet;
}