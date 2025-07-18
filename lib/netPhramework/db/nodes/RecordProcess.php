<?php

namespace netPhramework\db\nodes;

abstract class RecordProcess extends AssetResource implements RecordChild
{
	use HasRecord;

	public function enlist(AssetResourceSet $resourceSet):void
	{
		$resourceSet->recordChildSet->add($this);
	}
}