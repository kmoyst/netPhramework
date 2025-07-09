<?php

namespace netPhramework\db\presentation\recordTable\rowSet;

use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\locating\MutablePath;
use netPhramework\rendering\Encodable;

class RowSetFactory implements RowRegistry
{
	private array $rows = [];
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private Encodable $callbackInput;
	private MutablePath $assetPath;

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

	public function setCallbackInput(Encodable $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
		return $this;
	}

	public function setCompositePath(MutablePath $assetPath): self
	{
		$this->assetPath = $assetPath;
		return $this;
	}

	public function makeRowSet(array $collation):RowSet
	{
		return new RowSet($collation, $this);
	}

	/**
	 * @param string $id
	 * @return Row
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function getRow(string $id):Row
	{
		if(!isset($this->rows[$id]))
		{
			$record = $this->recordSet->getRecord($id);
			$this->rows[$id] = new Row(
				$this->columnSet, $record, $this->callbackInput,
				clone $this->assetPath);
		}
		return $this->rows[$id];
	}
}