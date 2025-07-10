<?php

namespace netPhramework\db\resources;

use netPhramework\core\ResourceIterator;
use netPhramework\exceptions\ResourceNotFound;

readonly class RecordChildSet extends ResourceIterator
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
	 * @throws ResourceNotFound
	 */
	public function get(string $resourceId):RecordChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}