<?php

namespace netPhramework\db\nodes;

abstract class AssetProcess extends AssetResource implements AssetChild
{
	use HasRecordSet;

	public function enlist(AssetResourceSet $resourceSet):void
	{
		$resourceSet->assetChildSet->add($this);
	}
}