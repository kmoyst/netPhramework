<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;

interface CompositeAdapter
{
	public function addAsset(Asset $asset):void;
}