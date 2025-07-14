<?php

namespace netPhramework\db\nodes;

abstract class RecordProcess extends AssetResource implements RecordChild
{
	use HasRecord;

	public function enlist(AssetResourceDepot $depot):void
	{
		$depot->recordChildSet->add($this);
	}
}