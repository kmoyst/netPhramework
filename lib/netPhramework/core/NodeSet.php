<?php
namespace netPhramework\core;
use Iterator;
class NodeSet implements Iterator
{
    private array $nodes = [];

	public function add(Node $node):void
	{
		$this->nodes[$node->getComponentName()] = $node;
	}

	public function has(string $name):bool
	{
		return array_key_exists($name, $this->nodes);
	}

	public function get(string $name):Node
	{
		return $this->nodes[$name];
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