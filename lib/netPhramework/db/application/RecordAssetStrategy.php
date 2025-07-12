<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Resource;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):Resource;
}