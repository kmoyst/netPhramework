<?php

namespace netPhramework\db\nodes;

use netPhramework\nodes\Resource;

abstract class AssetResource extends Resource
{
	abstract public function enlist(AssetResourceSet $resourceSet):void;
}