<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\Record;

trait HasRecord
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}