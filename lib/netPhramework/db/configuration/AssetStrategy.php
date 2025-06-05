<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;

interface AssetStrategy extends NodeStrategy
{
	public function create(RecordMapper $mapper):Asset;
}