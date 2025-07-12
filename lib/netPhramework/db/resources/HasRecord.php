<?php

namespace netPhramework\db\resources;

use netPhramework\db\core\Record;

trait HasRecord
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}