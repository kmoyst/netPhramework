<?php

namespace netPhramework\data\asset;

use netPhramework\data\record\Record;

trait HasRecord
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}