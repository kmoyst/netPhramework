<?php

namespace netPhramework\db\nodes;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class Branch extends Composite implements AssetRecordChild
{
	public function __construct
	(
	private readonly Asset  $asset,
	private readonly string $linkField
	)
	{}

	/**
	 * @param Record $record
	 * @return AssetRecordChild
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): AssetRecordChild
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