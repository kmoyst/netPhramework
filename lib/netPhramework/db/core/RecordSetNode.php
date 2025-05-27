<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;

abstract class RecordSetNode implements Node
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}