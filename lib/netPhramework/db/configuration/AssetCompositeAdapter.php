<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;

interface AssetCompositeAdapter
{
	public function addAsset(Asset $asset):void;
}