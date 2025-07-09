<?php

namespace netPhramework\db\core;

use netPhramework\common\IsIterable;

class RecordChildSet
{
	use IsIterable;

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