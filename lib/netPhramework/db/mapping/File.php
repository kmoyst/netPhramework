<?php

namespace netPhramework\db\mapping;

use netPhramework\db\core\Record;

abstract class File implements \netPhramework\responding\File
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}