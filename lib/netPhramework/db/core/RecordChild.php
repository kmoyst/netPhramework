<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\db\mapping\Record;

abstract class RecordChild implements Node
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}