<?php

namespace netPhramework\data\nodes;

abstract class RecordProcess extends RecordResource implements RecordNode
{
	use HasRecord;

	public function enlist(RecordResourceRegistry $registry):void
	{
		$registry->recordNodeSet->add($this);
	}
}