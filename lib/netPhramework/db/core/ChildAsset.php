<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Record;

class ChildAsset extends RecordChild
{
	use CompositeTrait;

	private Asset $asset;
	private string $linkField;

	/**
	 * @param Asset $asset
	 * @param string $linkField
	 */
	public function __construct(Asset $asset, string $linkField)
	{
		$this->asset = $asset;
		$this->linkField = $linkField;
	}

	/**
	 * @param Record $record
	 * @return RecordChild
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function setRecord(Record $record): RecordChild
	{
		$childRecords = $this->asset->getRecordSet();
		$field = $childRecords->getField($this->linkField);
		$condition = new Condition()
			->setField($field)
			->setValue($record->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $this;
	}

	public function getChild(string $name): Node
	{
		return $this->asset->getChild($name);
	}

	public function getName(): string
	{
		return $this->asset->getName();
	}
}