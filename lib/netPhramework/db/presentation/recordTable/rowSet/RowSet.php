<?php

namespace netPhramework\db\presentation\recordTable\rowSet;
use Countable;
use Iterator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

class RowSet implements Iterator, Countable
{
	private array $collation;
	private RowRegistry $registry;
	private int $pointer = 0;

	public function setCollation(array $collation): self
	{
		$this->collation = $collation;
		return $this;
	}

	public function setRegistry(RowRegistry $registry): self
	{
		$this->registry = $registry;
		return $this;
	}

	public function getIds():array
	{
		return $this->collation;
	}

	/**
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): Row
	{
		return $this->registry->getRow($this->collation[$this->pointer]);
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