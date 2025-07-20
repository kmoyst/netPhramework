<?php

namespace netPhramework\data\mapping;

use netPhramework\data\core\Record;
use netPhramework\data\core\RecordSet;
use netPhramework\data\core\RecordSetFactory;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;

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