<?php

namespace netPhramework\core;

use netPhramework\common\KeyedIterator;
use netPhramework\exceptions\ResourceNotFound;

class ResourceSet extends ResourceIterator
{
	use KeyedIterator;

	public function current(): Resource
	{
		return current($this->items);
	}

	/**
	 * @param string $resourceId
	 * @return Resource
	 * @throws ResourceNotFound
	 */
	public function get(string $resourceId):Resource
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}

	public function add(Resource $resource):self
	{
		$this->storeResource($resource);
		return $this;
	}
}