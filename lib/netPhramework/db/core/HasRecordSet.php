<?php

namespace netPhramework\db\core;

use netPhramework\db\mapping\RecordSet;

trait HasRecordSet
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}