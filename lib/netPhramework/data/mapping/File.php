<?php

namespace netPhramework\data\mapping;

use netPhramework\data\record\Record;

abstract class File implements \netPhramework\transferring\File
{
	protected Record $record;

	public function setRecord(Record $record): self
	{
		$this->record = $record;
		return $this;
	}
}