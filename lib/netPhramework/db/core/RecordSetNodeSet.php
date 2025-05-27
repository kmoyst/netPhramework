<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordSetNodeSet
{
	private array $nodes = [];

	/**
	 * @param string $name
	 * @return RecordSetNode
	 * @throws NotFound
	 */
	public function getNode(string $name):RecordSetNode
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}

	public function addNode(RecordSetNode $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}
}