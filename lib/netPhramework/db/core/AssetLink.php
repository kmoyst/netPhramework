<?php

namespace netPhramework\db\core;

use netPhramework\core\Composite;
use netPhramework\core\Node;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Record;

class AssetLink extends Composite implements RecordChild
{

	public function __construct
	(
	private readonly Asset $asset,
	private readonly string $linkField
	)
	{}

	/**
	 * @param Record $record
	 * @return RecordChild
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): RecordChild
	{
		$childRecords = $this->asset->recordSet;
		$field = $childRecords->getField($this->linkField);
		$condition = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $this;
	}

	public function getChild(string $id): Node
	{
		return $this->asset->getChild($id);
	}

	public function getName(): string
	{
		return $this->asset->getName();
	}
}