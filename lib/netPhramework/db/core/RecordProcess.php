<?php

namespace netPhramework\db\core;

abstract class RecordProcess extends Process
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}