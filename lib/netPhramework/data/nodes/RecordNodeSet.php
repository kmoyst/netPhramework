<?php

namespace netPhramework\data\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class RecordNodeSet extends ResourceIterator
{
	public function current(): RecordNode
	{
		return current($this->items);
	}

	public function add(RecordNode $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return RecordNode
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):RecordNode
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}