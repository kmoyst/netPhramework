<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\asset\Asset;
use netPhramework\data\record\RecordMapper;

interface AssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}