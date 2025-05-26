<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\core\Exception;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Glue;
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
		$ids   = [];
		$ids[0] = array_combine($allIds, $allIds);
		$glues = [];
		foreach($this->context->getConditionSet() as $i => $condition)
		{
			$field    = $condition[FilterKey::CONDITION_FIELD->value];
			$cOper	  = $condition[FilterKey::CONDITION_OPERATOR->value];
			$cValue	  = $condition[FilterKey::CONDITION_VALUE->value];
			$cGlue 	  = $condition[FilterKey::CONDITION_GLUE->value];
			if(($operator = Operator::tryFrom($cOper)) === null)
				throw new Exception("Invalid Operator: $cOper");
			if(($glue = Glue::tryFrom($cGlue)) === null)
				throw new Exception("Invalid Glue: $cGlue");
			$evalIds = $ids[0];
			foreach($ids[0] as $id)
			{
				$record = $this->recordSet->getRecord($id);
				try {
					$value = $record->getValue($field);
				} catch (FieldAbsent) {
					break;
				}
				if(!$operator->check($value, $cValue))
				{
					unset($evalIds[$id]);
				}
			}
			$ids[$i+1] = $evalIds;
			$glues[]  = $glue;
		}
		array_unshift($glues, Glue::AND);
		array_pop($glues);
		$filteredIds = $ids[0];
		foreach($glues as $i => $glue)
		{
			$filteredIds = $glue->check($filteredIds, $ids[$i+1]);
		}
		$ids = $filteredIds; // now start sorting
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