<?php

namespace netPhramework\db\presentation\recordTable\rowSet;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\Operator;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\mapping\SortDirection;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\db\presentation\recordTable\query\Key;
use netPhramework\db\presentation\recordTable\query\Query;

class Collator
{
	private Query $query;
	private RecordSet $recordSet;
	private ColumnSet $columnSet;

	private array  $unfilteredIds;
	private ?array $filteredIds = null;
	private ?array $sortedIds = null;
	private ?array $paginatedIds = null;

	public function setQuery(Query $query): self
	{
		$this->query = $query;
		return $this;
	}

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

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function initialize():self
	{
		$this->unfilteredIds = $this->recordSet->getIds();
		return $this;
	}


	/**
	 * @return $this
	 * @throws Exception
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function select():self
	{
		$allIds = array_combine($this->unfilteredIds, $this->unfilteredIds);
		$glues  = [];
		$ids    = [];
		foreach($this->query->getConditionSet() as $i => $condition)
		{
			$strOperator = $condition[Key::CONDITION_OPERATOR->value];
			$strGlue 	 = $condition[Key::CONDITION_GLUE->value] ?? '';
			if(($operator = Operator::tryFrom($strOperator)) === null)
				throw new Exception("Invalid Operator: $strOperator");
			if($strGlue !== '' && ($glue = Glue::tryFrom($strGlue)) === null)
				throw new Exception("Invalid Glue: $strGlue")
				;
			$field  = $condition[Key::CONDITION_FIELD->value];
			$value  = $condition[Key::CONDITION_VALUE->value];
			$column = $this->columnSet->getColumn($field);
			$currentConditionIds = $allIds;
			foreach($allIds as $id)
			{
				$record = $this->recordSet->getRecord($id);
				$rowValue = $column->getOperableValue($record);
				if(!$operator->check( // case insensitive
					strtolower($rowValue), strtolower($value)))
					unset($currentConditionIds[$id]);
			}
			$ids[$i]   = $currentConditionIds;
			$glues[$i] = $glue ?? '';
		}
		if(count($glues) > 0) {
			array_shift($glues);
			array_unshift($glues, Glue::AND);
		}
		$filteredIds = $allIds;
		foreach($glues as $i => $glue)
		{
			$filteredIds = $glue->check($filteredIds, $ids[$i]);
		}
		$this->filteredIds = array_keys($filteredIds);
		$this->query->setCount(count($filteredIds));
		return $this;
	}

	/**
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function sort():self
	{
		$ids  = $this->filteredIds ?? $this->unfilteredIds;
		$args = [];
		foreach($this->query->getSortArray() as $vector)
		{
			$field = $vector[Key::SORT_FIELD->value];
			$direction = $vector[Key::SORT_DIRECTION->value];
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
		$this->sortedIds = array_pop($args);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function paginate():self
	{
		if($this->query->getLimit() === null) return $this;
		$limit = $this->query->getLimit();
		$offset = $this->query->getOffset();
		$ids = $this->sortedIds ?? $this->filteredIds ?? $this->unfilteredIds;
		$this->paginatedIds = array_slice($ids, $offset, $limit);
		return $this;
	}

	public function getMap():CollationMap
	{
		return new CollationMap(
			$this->unfilteredIds,
			$this->filteredIds,
			$this->sortedIds,
			$this->paginatedIds);
	}
}