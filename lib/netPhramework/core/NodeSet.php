<?php

namespace netPhramework\core;

use netPhramework\common\IsKeyedIterable;
use netPhramework\exceptions\NodeNotFound;

class NodeSet extends NodeIterator
{
	use IsKeyedIterable;

	public function current(): Node
	{
		return current($this->items);
	}

	/**
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function get(string $id):Node
	{
		if(!$this->has($id)) throw new NodeNotFound("Not Found: $id");
		return $this->items[$id];
	}

	public function add(Node $node):self
	{
		$this->items[$node->getNodeId()] = $node;
		return $this;
	}
}