<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;

interface AssetNode extends Node
{
	public function enlist(Asset $asset):void;
}