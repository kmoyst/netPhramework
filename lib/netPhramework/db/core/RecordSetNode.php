<?php

namespace netPhramework\db\core;

use netPhramework\db\configuration\AssetNodeManager;

abstract class RecordSetNode implements AssetNode
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function enlist(AssetNodeManager $manager): void
	{
		$manager->addRecordSetNode($this);
	}
}