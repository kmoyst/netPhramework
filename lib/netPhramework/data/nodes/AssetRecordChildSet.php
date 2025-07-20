<?php

namespace netPhramework\data\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\ResourceIterator;

class AssetRecordChildSet extends ResourceIterator
{
	public function current(): AssetRecordChild
	{
		return current($this->items);
	}

	public function add(AssetRecordChild $child):self
	{
		$this->storeResource($child);
		return $this;
	}

	/**
	 * @param string $resourceId
	 * @return AssetRecordChild
	 * @throws NodeNotFound
	 */
	public function get(string $resourceId):AssetRecordChild
	{
		$this->confirmResource($resourceId);
		return $this->items[$resourceId];
	}
}