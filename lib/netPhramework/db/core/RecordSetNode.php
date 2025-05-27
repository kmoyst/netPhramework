<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;

abstract class RecordSetNode implements Node, Component
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function enlist(NodeManager $manager): void
	{
		$manager->addRecordSetNode($this);
	}
}