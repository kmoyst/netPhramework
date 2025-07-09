<?php

namespace netPhramework\core;

use Iterator;
use netPhramework\common\IsKeyedIterable;
use netPhramework\exceptions\ResourceNotFound;

abstract class ResourceIterator implements Iterator
{
	use IsKeyedIterable;

	protected function storeResource(Resource $resource):void
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

	abstract public function current():Resource;
}