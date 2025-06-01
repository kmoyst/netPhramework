<?php

namespace netPhramework\db\presentation\recordTable;

use Countable;
use Iterator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\mapping\RecordSet;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;

class RowSet implements Iterator, Countable
{
	private array $rows = [];
	private int $pointer = 0;
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private Input $callbackInput;
	private MutablePath $assetPath;
	private array $idsToTraverse;

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

	public function setCompositePath(MutablePath $assetPath): self
	{
		$this->assetPath = $assetPath;
		return $this;
	}

	public function setIdsToTraverse(array $idsToTraverse): self
	{
		$this->idsToTraverse = $idsToTraverse;
		return $this;
	}

	/**
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function current(): Row
	{
		$this->ensureRow($this->idsToTraverse[$this->pointer]);
		return $this->rows[$this->idsToTraverse[$this->pointer]];
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

	public function count(): int
	{
		return isset($this->idsToTraverse) ? count($this->idsToTraverse) : 0;
	}

	public function next(): void
	{
		++$this->pointer;
	}

	public function key(): int
	{
		return $this->idsToTraverse[$this->pointer];
	}

	public function valid(): bool
	{
		return $this->pointer < count($this->idsToTraverse);
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}
}