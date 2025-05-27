<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\core\Composite;

class RecordNodeComposite implements Component
{
	use Composite;

	private Record $record;
	private RecordNodeSet $nodeSet;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function setNodeSet(RecordNodeSet $nodeSet): self
	{
		$this->nodeSet = $nodeSet;
		return $this;
	}

	public function getChild(string $name): Component
	{
		$node = $this->nodeSet->getNode($name);
		$node->setRecord($this->record);
		return $node;
	}

	public function getName(): string
	{
		return $this->record->getId();
	}
}