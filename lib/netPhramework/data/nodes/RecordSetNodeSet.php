<?php

namespace netPhramework\data\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class RecordSetNodeSet extends ResourceIterator
{
	public function current(): RecordSetNode
	{
		return current($this->items);
	}

	public function add(RecordSetNode $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return RecordSetNode
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):RecordSetNode
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}