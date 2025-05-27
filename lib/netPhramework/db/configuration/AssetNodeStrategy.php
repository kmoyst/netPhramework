<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\AssetNode;

interface AssetNodeStrategy
{
	public function createNode(RecordAccess $access):AssetNode;
}