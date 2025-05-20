<?php

namespace netPhramework\db\presentation\recordTable;

use Iterator;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\rendering\Viewable;

readonly class Row implements Iterator
{
	public function __construct(
		private ColumnSet $columnSet,
		private Record $record) {}

	/**
	 * @return string|Viewable
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 */
	public function current(): string|Viewable
	{
		return $this->columnSet->current()->getViewableValue($this->record);
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