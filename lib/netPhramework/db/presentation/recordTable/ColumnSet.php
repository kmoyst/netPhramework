<?php

namespace netPhramework\db\presentation\recordTable;
use Iterator;

class ColumnSet implements Iterator
{
	private array $columns = [];
	private array $headers = [];

	public function add(Column $column):ColumnSet
	{
		$this->columns[$column->getName()] = $column;
		return $this;
	}

	public function getColumn(string $name):Column
	{
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