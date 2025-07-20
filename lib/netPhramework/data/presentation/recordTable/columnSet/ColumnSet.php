<?php

namespace netPhramework\data\presentation\recordTable\columnSet;
use Iterator;
use netPhramework\data\exceptions\ColumnAbsent;

class ColumnSet implements Iterator
{
	private array $columns = [];
	private array $headers = [];

	public function add(Column $column):ColumnSet
	{
		$this->columns[$column->getName()] = $column;
		return $this;
	}

	/**
	 * @param string $name
	 * @return Column
	 * @throws ColumnAbsent
	 */
	public function getColumn(string $name):Column
	{
		if(!isset($this->columns[$name]))
			throw new ColumnAbsent("Column absent: $name");
		return $this->columns[$name];
	}

	public function remove(string $name):ColumnSet
	{
		if(isset($this->columns[$name]))
			unset($this->columns[$name]);
		return $this;
	}

	public function clear():ColumnSet
	{
		$this->columns = [];
		return $this;
	}

	public function getNames():array
	{
		return array_keys($this->columns);
	}

	public function current(): Column
	{
		return current($this->columns);
	}

	public function next(): void
	{
		next($this->columns);
	}

	public function key(): string
	{
		return key($this->columns);
	}

	public function valid(): bool
	{
		return key($this->columns) !== null;
	}

	public function rewind(): void
	{
		reset($this->columns);
	}

	public function getHeaders():array
	{
		if(empty($this->headers))
			foreach($this as $name => $column)
				$this->headers[$name] = $column->getHeader();
		return $this->headers;
	}
}