<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordNodeSet
{
	private array $nodes = [];

	/**
	 * @param string $name
	 * @return RecordNode
	 * @throws NotFound
	 */
	public function getNode(string $name):RecordNode
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}

	public function addNode(RecordNode $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}
}