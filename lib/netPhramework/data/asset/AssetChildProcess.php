<?php

namespace netPhramework\data\asset;

abstract class AssetChildProcess extends AssetResource implements AssetChildNode
{
	use HasRecord;

	public function enlist(AssetResourceRegistry $registry):void
	{
		$registry->childNodeSet->add($this);
	}
}