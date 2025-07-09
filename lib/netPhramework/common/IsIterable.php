<?php

namespace netPhramework\common;

use netPhramework\core\Node;

trait IsIterable
{
	protected array $items = [];

	public function has(string $id):bool
	{
		return array_key_exists($id, $this->items);
	}

	public function getNames():array
	{
		return array_keys($this->items);
	}

	public function current(): Node
	{
		return current($this->items);
	}

	public function next(): void
	{
		next($this->items);
	}

	public function key(): string
	{
		return key($this->items);
	}

	public function valid(): bool
	{
		return key($this->items) !== null;
	}

	public function rewind(): void
	{
		reset($this->items);
	}
}