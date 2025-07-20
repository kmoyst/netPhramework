<?php

namespace netPhramework\data\presentation\recordTable\rowSet;

use Iterator;
use netPhramework\data\core\Record;
use netPhramework\data\presentation\recordTable\columnSet\Column;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\rendering\Encodable;

class CellSet implements Iterator
{
	private ColumnSet $columnSet;
	private Record $record;
	private array $cells = [];

	/**
	 * @param ColumnSet $columnSet
	 * @param Record $record
	 */
	public function __construct(ColumnSet $columnSet, Record $record)
	{
		$this->columnSet = $columnSet;
		$this->record = $record;
	}

	/**
	 * @return string|Encodable
	 */
	public function current(): string|Encodable
	{
		$column = $this->columnSet->current();
		$this->ensureCell($column);
		return $this->cells[$column->getName()];
	}

	private function ensureCell(Column $column):void
	{
		if(isset($this->cells[$column->getName()])) return;
		$this->cells[$column->getName()] = new Cell($column, $this->record);
	}

	public function next(): void
	{
		$this->columnSet->next();
	}

	public function key(): string
	{
		return $this->columnSet->key();
	}

	public function valid(): bool
	{
		return $this->columnSet->valid();
	}

	public function rewind(): void
	{
		$this->columnSet->rewind();
	}
}