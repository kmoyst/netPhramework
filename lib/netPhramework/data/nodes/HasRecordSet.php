<?php

namespace netPhramework\data\nodes;

use netPhramework\data\core\RecordSet;

trait HasRecordSet
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}