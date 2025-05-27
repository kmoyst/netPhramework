<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\Record;

class RecordComposite implements Node
{
	use CompositeTrait;

	private RecordNodeSet $nodeSet;
	private Record $record;

	public function setNodeSet(RecordNodeSet $nodeSet): self
	{
		$this->nodeSet = $nodeSet;
		return $this;
	}

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function getChild(string $name): Node
	{
		$node = $this->nodeSet->get($name);
		$node->setRecord($this->record);
		return $node;
	}

	public function getName(): string
	{
		return $this->record->getId();
	}
}