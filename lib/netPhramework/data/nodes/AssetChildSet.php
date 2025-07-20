<?php

namespace netPhramework\data\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class AssetChildSet extends ResourceIterator
{
	public function current(): AssetChild
	{
		return current($this->items);
	}

	public function add(AssetChild $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return AssetChild
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):AssetChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}