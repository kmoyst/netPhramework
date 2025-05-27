<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\core\CompositeTrait;

class RecordNodeComposite implements Node
{
	use CompositeTrait;

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

	public function getChild(string $name): Node
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