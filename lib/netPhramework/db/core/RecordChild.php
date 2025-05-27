<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Condition;

/**
 * Wrapper for Asset that includes parent information
 */
class RecordChild extends RecordNode implements OneToMany
{
	use CompositeTrait;

	private Asset $asset;
	private string $parentField;

	/**
	 * @param string $parentField
	 */
	public function __construct(string $parentField)
	{
		$this->parentField = $parentField;
	}

	public function setAsset(Asset $asset): self
	{
		$this->asset = $asset;
		return $this;
	}

	/** @inheritdoc  */
	public function getChildren(Record $record): RecordSet
	{
		return $this->getChildRecords( // it's probably uneccessary to clone
			$record, clone $this->asset->getRecordSet());
	}

	/**
	 * @param Record $parent
	 * @param RecordSet $childRecords
	 * @return RecordSet
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	private function getChildRecords(Record $parent,
									 RecordSet $childRecords):RecordSet
	{
		$field = $childRecords->getField($this->parentField);
		$condition = new Condition()
			->setField($field)
			->setValue($parent->getId())
		;
		$childRecords->reset()->addCondition($condition);
		return $childRecords;
	}

	public function getChild(string $name): Node
	{
		$this->getChildRecords($this->record, $this->asset->getRecordSet());
		return $this->asset->getChild($name);
	}

	public function getName(): string
	{
		return $this->asset->getName();
	}
}