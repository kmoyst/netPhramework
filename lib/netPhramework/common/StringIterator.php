<?php

namespace netPhramework\common;

use Iterator;

class StringIterator implements Iterator
{
	protected int $pointer = 0;

	public function __construct
	(
		protected readonly array $items
	) {}

	public function current(): string
	{
		return $this->items[$this->pointer];
	}

	public function next(): void
	{
		++$this->pointer;
	}

	public function key(): int
	{
		return $this->pointer;
	}

	public function valid(): bool
	{
		return $this->pointer < sizeof($this->items);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}
}