<?php

namespace netPhramework\db\core;

use netPhramework\core\Component;
use netPhramework\db\configuration\AssetNodeManager;

interface AssetNode extends Component
{
	public function enlist(AssetNodeManager $manager):void;
}