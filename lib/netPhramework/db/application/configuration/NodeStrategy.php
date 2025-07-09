<?php

namespace netPhramework\db\application\configuration;

use netPhramework\core\Node;
use netPhramework\db\application\mapping\RecordMapper;

interface NodeStrategy
{
	public function create(RecordMapper $mapper):Node;
}