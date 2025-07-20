<?php

namespace netPhramework\data\configuration\strategies;

use netPhramework\data\core\RecordMapper;
use netPhramework\data\nodes\RecordResource;

interface RecordResourceStrategy
{
	public function create(RecordMapper $mapper):RecordResource;
}