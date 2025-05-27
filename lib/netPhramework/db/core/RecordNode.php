<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;

abstract class RecordNode implements Node, Component
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function enlist(NodeManager $manager): void
	{
		$manager->addRecordNode($this);
	}
}