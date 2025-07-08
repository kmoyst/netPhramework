<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;
use netPhramework\db\core\AssetNode;

interface AssetStrategy extends AssetNodeStrategy
{
	public function create(RecordMapper $mapper):Asset;
}