<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\exceptions\Exception;

class RowSetBuilder
{
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private FilterContext $context;

	private array $sortedIds;

	public function setRecordSet(RecordSet $recordSet):self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function setContext(FilterContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function setColumnSet(ColumnSet $columnSet): self
	{
		$this->columnSet = $columnSet;
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function sort():RowSetBuilder
	{
		if($this->context->getSortField() === null)
			$ids = $this->recordSet->getIds();
		else
		{
			$sortableValues = [];
			$column = $this->columnSet->getColumn(
				$this->context->getSortField());
			foreach($this->recordSet as $id => $record)
				$sortableValues[$id] = $column->getSortableValue($record);
			$sortFlag = SORT_STRING | SORT_NATURAL | SORT_FLAG_CASE;
			if($this->context->getSortDirection() === 1)
				arsort($sortableValues, $sortFlag);
			else
				asort($sortableValues, $sortFlag);
			$ids = array_keys($sortableValues);
		}
		$this->sortedIds = array_slice(
			$ids, $this->context->getOffset(), $this->context->getLimit());
		return $this;
	}

	public function getRowSet():RowSet
	{
		return new RowSet($this->recordSet, $this->columnSet, $this->sortedIds);
	}
}