<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\asset\AssetResource;
use netPhramework\data\record\RecordMapper;

interface AssetResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}