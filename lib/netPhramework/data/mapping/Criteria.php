<?php

namespace netPhramework\data\mapping;

class Criteria implements DataSet
{
	/**
	 * @var Condition[]
	 */
	private array $conditions = [];
	private int $pointer = 0;

	public function add(Condition $condition):Criteria
	{
		$this->conditions[] = $condition;
		return $this;
	}

	public function current(): Condition
	{
		return $this->conditions[$this->pointer];
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
		return $this->pointer < $this->count();
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}

	public function count(): int
	{
		return count($this->conditions);
	}

	public function getFieldNames(): array
	{
		$names = [];
		foreach($this->conditions as $condition)
			$names[] = $condition->getField()->getName();
		return $names;
	}

	public function isEmpty():bool
	{
		return $this->count() === 0;
	}
}