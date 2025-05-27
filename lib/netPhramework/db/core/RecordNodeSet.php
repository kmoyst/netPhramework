<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordNodeSet
{
	private array $nodes = [];

	public function add(RecordNode $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordNode
	 * @throws NotFound
	 */
	public function get(string $name):RecordNode
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}