<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\ColumnAbsent;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\Operator;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\mapping\SortDirection;

class RowSetBuilder
{
	private RecordSet $recordSet;
	private ColumnSet $columnSet;
	private FilterContext $context;

	private array $filteredIds;
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
	 * @throws Exception
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function filter():RowSetBuilder
	{
		$rsIds  = $this->recordSet->getIds();
		$allIds = array_combine($rsIds, $rsIds);
		$glues  = []; // populated by glue at the beginning of condition
		$ids    = []; // multidimensional array per condition
		foreach($this->context->getConditionSet() as $i => $condition)
		{
			$strOperator = $condition[FilterKey::CONDITION_OPERATOR->value];
			$strGlue 	 = $condition[FilterKey::CONDITION_GLUE->value] ?? '';
			if(($operator = Operator::tryFrom($strOperator)) === null)
				throw new Exception("Invalid Operator: $strOperator");
			if($strGlue !== '' && ($glue = Glue::tryFrom($strGlue)) === null)
				throw new Exception("Invalid Glue: $strGlue")
				;
			$field  = $condition[FilterKey::CONDITION_FIELD->value];
			$value  = $condition[FilterKey::CONDITION_VALUE->value];
			try {
				$column = $this->columnSet->getColumn($field);
			} catch (ColumnAbsent) {
				break;
			}
			$currentConditionIds = $allIds;
			foreach($allIds as $id)
			{
				$record 	 = $this->recordSet->getRecord($id);
				$recordValue = $column->getFilterableValue($record);
				if(!$operator->check($recordValue, $value))
					unset($currentConditionIds[$id]);
			}
			$ids[$i]   = $currentConditionIds;
			$glues[$i] = $glue ?? '';
		}
		if(count($glues) > 0) {
			array_shift($glues);
			array_unshift($glues, Glue::AND); // AND first condition
		}
		$filteredIds = $allIds; // "full universe" is true
		foreach($glues as $i => $glue)
		{
			$filteredIds = $glue->check($filteredIds, $ids[$i]);
		}
		$this->filteredIds = $filteredIds;
		$this->context->setCount(count($this->filteredIds));
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
		$ids  = $this->filteredIds;
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