<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\nodes\Asset;
use netPhramework\data\record\RecordMapper;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}