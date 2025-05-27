<?php

namespace netPhramework\db\core;

use netPhramework\core\Node;
use netPhramework\db\configuration\AssetNodeManager;

interface AssetNode extends Node
{
	public function enlist(AssetNodeManager $manager):void;
}