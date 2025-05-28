<?php

namespace netPhramework\db\presentation\recordTable;

use Iterator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\mapping\RecordSet;
use netPhramework\dispatching\MutablePath;
use netPhramework\presentation\FormInput\Input;

class RowSet implements Iterator
{
	private array $rows = [];
	private int $pointer = 0;

	public function __construct(
		private readonly RecordSet $recordSet,
		private readonly ColumnSet $columnSet,
		private readonly array $recordIds,
		private readonly Input $callbackInput,
		private readonly MutablePath $assetPath
	) {}

	/**
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): Row
	{
		$this->ensureRow($this->recordIds[$this->pointer]);
		return $this->rows[$this->recordIds[$this->pointer]];
	}

	/**
	 * @param string $id
	 * @return void
	 * @throws RecordNotFound
	 * @throws MappingException
	 */
	private function ensureRow(string $id):void
	{
		if(isset($this->rows[$id])) return;
		$record = $this->recordSet->getRecord($id);
		$this->rows[$id] = new Row(
			$this->columnSet, $record, $this->callbackInput,
			clone $this->assetPath);
	}

	public function next(): void
	{
		++$this->pointer;
	}

	public function key(): int
	{
		return $this->recordIds[$this->pointer];
	}

	public function valid(): bool
	{
		return $this->pointer < count($this->recordIds);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}
}