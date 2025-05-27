<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordSetNodeSet
{
	private array $nodes = [];

	public function add(RecordSetNode $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordSetNode
	 * @throws NotFound
	 */
	public function get(string $name):RecordSetNode
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}