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
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private Input $callbackInput;
	private MutablePath $assetPath;
	private array $orderedIds;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function setColumnSet(ColumnSet $columnSet): self
	{
		$this->columnSet = $columnSet;
		return $this;
	}

	public function setCallbackInput(Input $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
		return $this;
	}

	public function setAssetPath(MutablePath $assetPath): self
	{
		$this->assetPath = $assetPath;
		return $this;
	}

	public function setOrderedIds(array $orderedIds): self
	{
		$this->orderedIds = $orderedIds;
		return $this;
	}

	/**
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): Row
	{
		$this->ensureRow($this->orderedIds[$this->pointer]);
		return $this->rows[$this->orderedIds[$this->pointer]];
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
		return $this->orderedIds[$this->pointer];
	}

	public function valid(): bool
	{
		return $this->pointer < count($this->orderedIds);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}
}