<?php

namespace netPhramework\data\asset;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class AssetNodeSet extends ResourceIterator
{
	public function current(): AssetNode
	{
		return current($this->items);
	}

	public function add(AssetNode $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return AssetNode
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):AssetNode
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}