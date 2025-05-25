<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\core\Exception;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\SortDirection;

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
		if(empty($this->context->getSortArray()) ||
		$this->context->getSortArray()[0][FilterKey::SORT_FIELD->value] === '')
			$ids = $this->recordSet->getIds();
		else
		{

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