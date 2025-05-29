<?php

namespace netPhramework\db\presentation\helpers;

use netPhramework\db\mapping\Record;

abstract class File implements \netPhramework\core\File
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}