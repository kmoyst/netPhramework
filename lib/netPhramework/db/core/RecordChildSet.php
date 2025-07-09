<?php

namespace netPhramework\db\core;

use netPhramework\core\NodeIterator;

class RecordChildSet extends NodeIterator
{
	public function current(): RecordChild
	{
		return current($this->items);
	}

	public function add(RecordChild $child):self
	{
		$this->items[$child->getNodeId()] = $child;
		return $this;
	}

	public function get(string $name):RecordChild
	{
		return $this->items[$name];
	}
}