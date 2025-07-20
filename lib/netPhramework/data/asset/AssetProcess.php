<?php

namespace netPhramework\data\asset;

abstract class AssetProcess extends AssetResource implements AssetChild
{
	use HasRecordSet;

	public function enlist(AssetResourceRegistry $registry):void
	{
		$registry->assetChildSet->add($this);
	}
}