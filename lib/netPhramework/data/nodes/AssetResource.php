<?php

namespace netPhramework\data\nodes;

use netPhramework\nodes\Resource;

abstract class AssetResource extends Resource
{
	abstract public function enlist(AssetResourceRegistry $registry):void;
}