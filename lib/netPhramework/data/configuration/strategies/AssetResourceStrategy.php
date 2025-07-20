<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\core\RecordMapper;
use netPhramework\data\nodes\AssetResource;

interface AssetResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}