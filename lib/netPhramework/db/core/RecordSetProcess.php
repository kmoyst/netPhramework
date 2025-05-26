<?php

namespace netPhramework\db\core;


abstract class RecordSetProcess extends Process
{
	protected RecordSet $recordSet;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}
}