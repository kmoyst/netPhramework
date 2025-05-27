<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\core\CompositeTrait;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Operator;

class ChildNode extends RecordNode
{
	use CompositeTrait;

	private Asset $child;
	private string $parentIdField;

	public function getChild(string $name): Node
	{
		$recordSet  = $this->child->getRecordSet();
		$field		= $recordSet->getField($this->parentIdField);
		$condition  = new Condition()
			->setField($field)
			->setOperator(Operator::EQUAL)
			->setValue($this->record->getId());
		$recordSet->reset()->addCondition($condition);
		return $this->child->getChild($name);
	}

	public function getName(): string
	{
		return $this->child->getName();
	}
}