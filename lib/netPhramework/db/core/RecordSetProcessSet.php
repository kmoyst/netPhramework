<?php

namespace netPhramework\db\core;

use netPhramework\exceptions\NotFound;

class RecordSetProcessSet
{
	private array $nodes = [];

	public function add(RecordSetProcess $node):self
	{
		$this->nodes[$node->getName()] = $node;
		return $this;
	}

	/**
	 * @param string $name
	 * @return RecordSetProcess
	 * @throws NotFound
	 */
	public function get(string $name):RecordSetProcess
	{
		if(!isset($this->nodes[$name]))
			throw new NotFound("Not Found: $name");
		return $this->nodes[$name];
	}
}