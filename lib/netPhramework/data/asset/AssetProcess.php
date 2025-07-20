<?php

namespace netPhramework\data\asset;

abstract class AssetProcess extends AssetResource implements AssetNode
{
	use HasRecordSet;

	public function enlist(AssetResourceRegistry $registry):void
	{
		$registry->nodeSet->add($this);
	}
}