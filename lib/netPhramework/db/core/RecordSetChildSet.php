<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordSetChildSet
{
	private array $nodes = [];

	public function add(RecordSetChild $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordSetChild
	 * @throws NotFound
	 */
	public function get(string $name):RecordSetChild
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}