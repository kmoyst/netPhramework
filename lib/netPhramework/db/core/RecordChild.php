<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\Record;

trait RecordChild
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}

	public function enlist(Asset $asset):void
	{
		$asset->recordChildSet->add($this);
	}
}