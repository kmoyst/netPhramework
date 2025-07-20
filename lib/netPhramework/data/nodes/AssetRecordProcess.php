<?php

namespace netPhramework\data\nodes;

abstract class AssetRecordProcess extends AssetResource implements AssetRecordChild
{
	use HasRecord;

	public function enlist(AssetResourceRegistry $registry):void
	{
		$registry->recordChildSet->add($this);
	}
}