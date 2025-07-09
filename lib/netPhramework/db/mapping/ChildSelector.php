<?php

namespace netPhramework\db\mapping;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;

class ChildSelector
{
	private RecordSetFactory $factory;
	private string $mappedName;
	private string $linkField;
	private string $assetName;

	public function __construct(
		RecordSetFactory $factory,
		string $mappedName,
		string $linkField,
		?string $assetName = null)
	{
		$this->factory = $factory;
		$this->mappedName = $mappedName;
		$this->linkField = $linkField;
		$this->assetName = $assetName ?? $mappedName;
	}


	/**
	 * @param Record $record
	 * @return RecordSet
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function getChildren(Record $record):RecordSet
	{
		$childRecords = $this->factory->recordsFor($this->mappedName);
		$field 		  = $childRecords->getField($this->linkField);
		$condition 	  = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $childRecords;
	}

	public function getAssetName(): string
	{
		return $this->assetName;
	}
}