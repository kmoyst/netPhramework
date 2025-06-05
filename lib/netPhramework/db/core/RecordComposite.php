<?php

namespace netPhramework\db\core;

use netPhramework\core\CompositeTrait;
use netPhramework\core\Node;

class RecordComposite extends RecordSetChild
{
	use CompositeTrait;

	private RecordChildSet $nodeSet;
	private string $recordId;

	public function setNodeSet(RecordChildSet $nodeSet): self
	{
		$this->nodeSet = $nodeSet;
		return $this;
	}

	public function setRecordId(string $recordId): self
	{
		$this->recordId = $recordId;
		return $this;
	}

	public function getChild(string $name): Node
	{
		$node = $this->nodeSet->get($name);
		$node->setRecord($this->recordSet->getRecord($this->recordId));
		return $node;
	}

	public function getName(): string
	{
		return $this->recordId;
	}
}