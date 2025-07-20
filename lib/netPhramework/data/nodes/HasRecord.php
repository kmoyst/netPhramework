<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\Record;

trait HasRecord
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}