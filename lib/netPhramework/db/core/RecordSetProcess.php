<?php

namespace netPhramework\db\core;

use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\db\mapping\RecordSet;

abstract class RecordSetProcess implements Node
{
	use LeafTrait;

	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}