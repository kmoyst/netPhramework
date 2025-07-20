<?php

namespace netPhramework\data\mapping;
use Iterator;

class SortArray implements Iterator
{
	private array $vectors = [];
	private int   $pointer = 0;

	public function current(): SortVector
	{
		return $this->vectors[$this->pointer];
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
		return $this->pointer > count($this->vectors);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}
}