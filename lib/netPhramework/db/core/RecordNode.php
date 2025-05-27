<?php

namespace netPhramework\db\core;

use netPhramework\db\configuration\AssetNodeManager;

abstract class RecordNode implements AssetNode
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function enlist(AssetNodeManager $manager): void
	{
		$manager->addRecordNode($this);
	}
}