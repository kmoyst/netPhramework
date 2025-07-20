<?php

namespace netPhramework\data\mapping;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\record\Record;
use netPhramework\data\record\RecordSet;
use netPhramework\data\record\RecordSetFactory;

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