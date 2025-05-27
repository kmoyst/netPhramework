<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\Condition;

/**
 * Wrapper for Asset that includes parent information
 */
class RecordChild extends RecordNode
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

	public function getChild(string $name): Node
	{
		$childSet = $this->asset->getRecordSet();
		$field = $childSet->getField($this->parentField);
		$condition = new Condition()
			->setField($field)
			->setValue($this->record->getId())
			;
		$childSet->reset()->addCondition($condition);
		return $this->asset->getChild($name);
	}

	public function getName(): string
	{
		return $this->asset->getName();
	}
}