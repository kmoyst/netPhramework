<?php

namespace netPhramework\db\nodes;

use netPhramework\db\core\RecordSet;

trait HasRecordSet
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}