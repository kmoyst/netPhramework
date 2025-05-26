<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\core\Record;

class OneToMany
{
	public function __construct(
		private readonly RecordSet $parentRecords,
		private readonly RecordSet $childRecords,
		private readonly string $linkFieldName) {}

	/**
	 * @param Record $record
	 * @return RecordSet
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getChildren(Record $record):RecordSet
	{
		$field 	   = $this->parentRecords->getField($this->linkFieldName);
		$linkId    = $record->getValue($this->linkFieldName);
		$condition = new Condition()->setField($field)->setValue($linkId);
		$this->childRecords->reset()->addCondition($condition);
		return $this->childRecords;
	}
}