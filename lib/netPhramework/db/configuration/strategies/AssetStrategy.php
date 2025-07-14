<?php

namespace netPhramework\db\configuration\strategies;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\nodes\Asset;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}