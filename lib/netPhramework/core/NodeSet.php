<?php

namespace netPhramework\core;

use Iterator;
use netPhramework\common\IsKeyedIterable;
use netPhramework\exceptions\NodeNotFound;

class NodeSet implements Iterator
{
	use IsKeyedIterable;

	/**
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function get(string $id):Node
	{
		$this->confirmNode($id);
		return $this->items[$id];
	}

	public function add(Node $node):self
	{
		$this->storeNode($node);
		return $this;
	}

	/**
	 * @param Node $node
	 * @return void
	 */
	protected function storeNode(Node $node):void
	{
		$this->items[$node->getNodeId()] = $node;
	}

	/**
	 * @param string $id
	 * @return void
	 * @throws NodeNotFound
	 */
	protected function confirmNode(string $id):void
	{
		if(!$this->has($id)) throw new NodeNotFound("Not Found: $id");
	}
}