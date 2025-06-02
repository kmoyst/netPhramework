<?php

namespace netPhramework\db\presentation\recordTable;
use Iterator;
use Countable;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

class RowSet implements Iterator, Countable
{
	private array $traversible;
	private int $pointer = 0;
	private RowFactory $factory;

	public function setTraversible(array $traversible): self
	{
		$this->traversible = $traversible;
		return $this;
	}

	public function setFactory(RowFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	/**
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): Row
	{
		return $this->factory->getRow($this->traversible[$this->pointer]);
	}

	public function next(): void
	{
		++$this->pointer;
	}

	public function key(): string
	{
		return $this->traversible[$this->pointer];
	}

	public function valid(): bool
	{
		return $this->pointer < count($this->traversible);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}

	public function count(): int
	{
		return count($this->traversible);
	}
}