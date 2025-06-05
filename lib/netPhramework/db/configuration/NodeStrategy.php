<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Node;

interface NodeStrategy
{
	public function create(RecordMapper $mapper):Node;
}