<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;

abstract class RecordNode implements Node
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}