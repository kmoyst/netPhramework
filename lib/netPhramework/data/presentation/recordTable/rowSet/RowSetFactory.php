<?php

namespace netPhramework\data\presentation\recordTable\rowSet;

use netPhramework\data\core\RecordSet;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\rendering\Encodable;
use netPhramework\routing\Path;

class RowSetFactory implements RowRegistry
{
	private array $rows = [];
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private Encodable $callbackInput;
	private Path $assetPath;

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

	public function setCompositePath(Path $assetPath): self
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