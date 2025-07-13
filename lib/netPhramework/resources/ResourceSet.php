<?php

namespace netPhramework\resources;

use netPhramework\common\KeyedIterator;
use netPhramework\exceptions\ResourceNotFound;

class ResourceSet extends ResourceIterator
{
	use KeyedIterator;

	public function current(): Node
	{
		return current($this->items);
	}

	/**
	 * @param string $resourceId
	 * @return Node
	 * @throws ResourceNotFound
	 */
	public function get(string $resourceId):Node
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}

	public function add(Node $resource):self
	{
		$this->storeResource($resource);
		return $this;
	}
}