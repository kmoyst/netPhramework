<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}