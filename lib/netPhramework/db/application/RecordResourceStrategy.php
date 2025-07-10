<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\resources\AssetResource;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}