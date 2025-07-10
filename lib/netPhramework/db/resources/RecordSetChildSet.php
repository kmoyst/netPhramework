<?php

namespace netPhramework\db\resources;

use netPhramework\core\ResourceIterator;
use netPhramework\exceptions\ResourceNotFound;

readonly class RecordSetChildSet extends ResourceIterator
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
	 * @throws ResourceNotFound
	 */
	public function get(string $resourceId):RecordSetChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}