<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\assets\Asset;

interface RecordAssetStrategy
{
	public function create(RecordMapper $mapper):Asset;
}