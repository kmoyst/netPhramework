<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NodeNotFound;

class RecordChildSet
{
	private array $nodes = [];

	public function add(RecordChild $node):self
	{
		$this->nodes[$node->getNodeId()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordChild
	 * @throws NodeNotFound
	 */
	public function get(string $name):RecordChild
	{
		if(!isset($this->nodes[$name]))
			throw new NodeNotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}