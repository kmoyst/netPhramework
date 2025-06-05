<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

abstract class RecordSetChild implements Node
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}