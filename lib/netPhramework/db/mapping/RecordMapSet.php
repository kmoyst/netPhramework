<?php

namespace netPhramework\db\mapping;

use Iterator;
use netPhramework\common\IsKeyedIterable;

class RecordMapSet implements Iterator
{
	use IsKeyedIterable;

	public function current(): RecordMap
	{
		return current($this->items);
	}

	public function add(RecordMap $map):self
	{
		$this->items[$map->assetName] = $map;
		return $this;
	}

	public function get(string $name):RecordMap
	{
		return $this->items[$name];
	}
}