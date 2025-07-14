<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\nodes\AssetResource;

interface AssetResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}