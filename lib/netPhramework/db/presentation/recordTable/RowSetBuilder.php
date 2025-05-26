<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\core\Exception;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Operator;
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
		$args = [];
		$allIds = $this->recordSet->getIds();
		$ids  = array_combine($allIds, $allIds);
		foreach($this->context->getConditionSet() as $condition)
		{
			$field    = $condition[FilterKey::CONDITION_FIELD->value];
			$operator = $condition[FilterKey::CONDITION_OPERATOR->value];
			$cValue	  = $condition[FilterKey::CONDITION_VALUE->value];
			if(($enum = Operator::tryFrom($operator)) === null)
				continue;
			foreach($ids as $id)
			{
				$record = $this->recordSet->getRecord($id);
				$value = $record->getValue($field); // throws FieldAbsent
				if(!$enum->check($value, $cValue)) unset($ids[$id]);
			}
		}
		foreach($this->context->getSortArray() as $vector)
		{
			$field = $vector[FilterKey::SORT_FIELD->value];
			$direction = $vector[FilterKey::SORT_DIRECTION->value];
			if(empty($field)) break;
			$parsedDirection = SortDirection::tryFrom($direction);
			$column = $this->columnSet->getColumn($field);
			$values = [];
			foreach($ids as $id)
			{
				$record = $this->recordSet->getRecord($id);
				$values[] = $column->getSortableValue($record);
			}
			$args[] = $values;
			$args[] = $parsedDirection ===
				SortDirection::DESCENDING ? SORT_DESC : SORT_ASC;
			$args[] = SORT_STRING | SORT_NATURAL | SORT_FLAG_CASE;
		}
		$args[] = $ids;
		array_multisort(...$args);
		$sortedIds = array_pop($args);
		$limit = $this->context->getLimit();
		$offset = $this->context->getOffset();
		$this->sortedIds = array_slice($sortedIds, $offset, $limit);
		return $this;
	}

	public function getRowSet():RowSet
	{
		return new RowSet($this->recordSet, $this->columnSet, $this->sortedIds);
	}
}