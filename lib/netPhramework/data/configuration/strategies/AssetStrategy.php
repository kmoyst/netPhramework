<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\core\RecordMapper;
use netPhramework\data\nodes\Asset;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}