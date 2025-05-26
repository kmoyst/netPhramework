<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;

abstract class RecordComponent implements Component
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}


}