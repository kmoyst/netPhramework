<?php

namespace netPhramework\db\mapping;

abstract class File implements \netPhramework\core\File
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}