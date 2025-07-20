<?php

namespace netPhramework\data\presentation\recordTable\rowSet;
use Countable;
use Iterator;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;

class RowSet implements Iterator, Countable
{
	private int $pointer = 0;

	public function __construct(
		private readonly array $collation,
		private readonly RowRegistry $registry) {}

	public function getIds():array
	{
		return $this->collation;
	}

	/**
	 * @param string $id
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function getRow(string $id):Row
	{
		return $this->registry->getRow($id);
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