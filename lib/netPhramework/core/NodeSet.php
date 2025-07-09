<?php

namespace netPhramework\core;

use Iterator;
use netPhramework\exceptions\NodeNotFound;

class NodeSet implements Iterator
{
	protected array $nodes = [];

	public function has(string $id):bool
	{
		return array_key_exists($id, $this->nodes);
	}

	/**
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function get(string $id):Node
	{
		$this->confirmNode($id);
		return $this->nodes[$id];
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
		$this->nodes[$node->getNodeId()] = $node;
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

	public function getNames():array
	{
		return array_keys($this->nodes);
	}

	public function current(): Node
	{
		return current($this->nodes);
	}

	public function next(): void
	{
		next($this->nodes);
	}

	public function key(): string
	{
		return key($this->nodes);
	}

	public function valid(): bool
	{
		return key($this->nodes) !== null;
	}

	public function rewind(): void
	{
		reset($this->nodes);
	}
}