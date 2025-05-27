<?php

namespace netPhramework\db\configuration;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\mapping\Record;

class OneToMany
{
	private string $linkField;
	private RecordSet $recordSet;

	/**
	 * @param string $linkField
	 * @param RecordSet $recordSet
	 */
	public function __construct(string $linkField, RecordSet $recordSet)
	{
		$this->linkField = $linkField;
		$this->recordSet = $recordSet;
	}

	/**
	 * @param Record $record
	 * @return RecordSet
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getChildren(Record $record):RecordSet
	{
		$childRecords = $this->recordSet;
		$field = $childRecords->getField($this->linkField);
		$condition = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $this->recordSet;
	}
}