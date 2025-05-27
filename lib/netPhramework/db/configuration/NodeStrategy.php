<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Node;

interface NodeStrategy
{
	public function createNode(RecordAccess $access):Node;
}