<?php

namespace netPhramework\db\application;

use netPhramework\db\resources\AssetResource;
use netPhramework\db\core\RecordMapper;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):AssetResource;
}