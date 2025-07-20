<?php

namespace netPhramework\data\asset;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Condition;
use netPhramework\data\record\Record;
use netPhramework\nodes\Composite;
use netPhramework\nodes\Node;

class AssetBranch extends Composite implements AssetChildNode
{
	public function __construct
	(
	private readonly Asset  $asset,
	private readonly string $linkField
	)
	{}

	/**
	 * @param Record $record
	 * @return AssetChildNode
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): AssetChildNode
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