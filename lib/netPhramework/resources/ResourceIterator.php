<?php

namespace netPhramework\resources;

use Iterator;
use netPhramework\common\KeyedIterator;
use netPhramework\exceptions\ResourceNotFound;

abstract class ResourceIterator implements Iterator
{
	use KeyedIterator;

	protected function storeResource(Node $resource):void
	{
		$this->items[$resource->getResourceId()] = $resource;
	}

	/**
	 * @param string $resourceId
	 * @return void
	 * @throws ResourceNotFound
	 */
	protected function confirmResource(string $resourceId):void
	{
		if(!$this->has($resourceId))
			throw new ResourceNotFound("Not Found: $resourceId");
	}

	abstract public function current():Node;
}