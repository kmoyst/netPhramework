<?php

namespace netPhramework\db\core;

use Iterator;
use netPhramework\common\KeyedIterator;

class RecordMapSet implements Iterator
{
	use KeyedIterator;

	public function current(): RecordMap
	{
		return current($this->items);
	}

	public function add(RecordMap $map):self
	{
		$this->items[$map->resourceName] = $map;
		return $this;
	}

	public function get(string $name):?RecordMap
	{
		return $this->items[$name] ?? null;
	}
}