<?php

namespace netPhramework\db\core;

use netPhramework\common\IsIterable;

class RecordSetChildSet
{
	use IsIterable;

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