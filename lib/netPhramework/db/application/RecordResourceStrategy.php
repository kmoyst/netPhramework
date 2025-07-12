<?php

namespace netPhramework\db\application;

use netPhramework\db\nodes\Asset;
use netPhramework\db\core\RecordMapper;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):Asset;
}