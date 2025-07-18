<?php

namespace netPhramework\db\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class RecordChildSet extends ResourceIterator
{
	public function current(): RecordChild
	{
		return current($this->items);
	}

	public function add(RecordChild $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return RecordChild
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):RecordChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}