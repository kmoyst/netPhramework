<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Exchange;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Operator;
use netPhramework\dispatching\redirectors\RedirectToChild;

class ChildNode extends RecordNode
{
	private Asset $child;
	private string $parentIdField;

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}

	public function getChild(string $name): Component
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