<?php

namespace netPhramework\db\presentation\recordTable;
use Iterator;
use Countable;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

class RowSet implements Iterator, Countable
{
	private array $collation;
	private int $pointer = 0;
	private RowFactory $factory;

	public function setCollation(array $collation): self
	{
		$this->collation = $collation;
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
		return $this->factory->getRow($this->collation[$this->pointer]);
	}

	public function next(): void
	{
		++$this->pointer;
	}

	public function key(): string
	{
		return $this->collation[$this->pointer];
	}

	public function valid(): bool
	{
		return $this->pointer < count($this->collation);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}

	public function count(): int
	{
		return count($this->collation);
	}
}