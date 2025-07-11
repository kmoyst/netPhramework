<?php

namespace netPhramework\db\core;

trait HasRecord
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}