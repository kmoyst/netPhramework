<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\assets\AssetResource;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}