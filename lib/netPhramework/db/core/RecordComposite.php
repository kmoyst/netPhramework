<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Composite;
use netPhramework\core\Exception;
use netPhramework\exceptions\ComponentNotFound;

class RecordComposite extends Composite
{
	private Record $record;
	private RecordComponentSet $componentSet;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function setComponentSet(RecordComponentSet $componentSet): self
	{
		$this->componentSet = $componentSet;
		return $this;
	}

	public function getChild(string $name): Component
	{
		$component = $this->componentSet->getComponent($name);
		$component->setRecord($this->record);
		return $component;
	}

	public function getName(): string
	{
		return $this->record->getId();
	}
}