<?php

namespace netPhramework\db\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class RecordSetChildSet extends ResourceIterator
{
	public function current(): RecordSetChild
	{
		return current($this->items);
	}

	public function add(RecordSetChild $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return RecordSetChild
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):RecordSetChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}