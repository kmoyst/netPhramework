<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\nodes\Resource;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):Resource;
}