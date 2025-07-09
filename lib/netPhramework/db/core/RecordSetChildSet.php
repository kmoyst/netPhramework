<?php

namespace netPhramework\db\core;

use netPhramework\core\NodeIterator;

class RecordSetChildSet extends NodeIterator
{
	public function current(): RecordSetChild
	{
		return current($this->items);
	}

	public function add(RecordSetChild $child):self
	{
		$this->items[$child->getNodeId()] = $child;
		return $this;
	}

	public function get(string $name):RecordSetChild
	{
		return $this->items[$name];
	}
}