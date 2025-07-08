<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\RecordSet;

trait RecordSetChild
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function enlist(Asset $asset):void
	{
		$asset->recordSetChildSet->add($this);
	}
}