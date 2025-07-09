<?php

namespace netPhramework\db\core;
use Iterator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

readonly class RecordOptions implements Iterator
{
	public function __construct(
		private RecordSet $recordSet,
		private RecordDescriber $describer) {}

	/**
	 * @return string
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): string
	{
		return $this->describer->describe($this->recordSet->current());
	}

	public function next(): void
	{
		$this->recordSet->next();
	}

	public function key(): string
	{
		return $this->recordSet->key();
	}

	public function valid(): bool
	{
		return $this->recordSet->valid();
	}

	/**
	 * @return void
	 * @throws MappingException
	 */
	public function rewind(): void
	{
		$this->recordSet->rewind();
	}
}