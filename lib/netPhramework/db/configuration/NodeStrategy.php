<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Node;

interface NodeStrategy
{
	public function createNode(RecordAccess $access):Node;
}