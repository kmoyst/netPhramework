<?php

namespace netPhramework\data\nodes;

abstract class RecordSetProcess extends RecordResource implements RecordSetNode
{
	use HasRecordSet;

	public function enlist(RecordResourceRegistry $registry):void
	{
		$registry->nodeSet->add($this);
	}
}