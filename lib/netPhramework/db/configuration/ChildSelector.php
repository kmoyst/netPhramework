<?php

namespace netPhramework\db\configuration;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\mapping\Record;

class ChildSelector
{
	private string $assetName;
	private string $linkField;
	private RecordSet $recordSet;

	public function __construct(
		string $assetName,
		string $linkField,
		RecordSet $recordSet)
	{
		$this->assetName = $assetName;
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

	public function getAssetName(): string
	{
		return $this->assetName;
	}
}