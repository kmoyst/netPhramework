<?php

namespace netPhramework\data\asset;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class AssetChildNodeSet extends ResourceIterator
{
	public function current(): AssetChildNode
	{
		return current($this->items);
	}

	public function add(AssetChildNode $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return AssetChildNode
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):AssetChildNode
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}