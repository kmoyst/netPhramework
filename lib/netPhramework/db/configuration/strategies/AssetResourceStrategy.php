<?php

namespace netPhramework\db\configuration\strategies;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\nodes\AssetResource;

interface AssetResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}