<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NodeNotFound;

class RecordSetChildSet
{
	private array $nodes = [];

	public function add(RecordSetChild $node):self
	{
		$this->nodes[$node->getComponentName()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordSetChild
	 * @throws NodeNotFound
	 */
	public function get(string $name):RecordSetChild
	{
		if(!isset($this->nodes[$name]))
			throw new NodeNotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}