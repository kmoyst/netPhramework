<?php

namespace netPhramework\db\presentation\recordTable\collation;
use netPhramework\core\Exception;
use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\ColumnAbsent;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\InvalidComparator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\SortDirection;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;

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
		$filtered  = $this->unfilteredIds;
		if($result = $this->checkConditions())
		{
			$collations = $result->getCollations();
			$glues  	= $result->getGlues();
			$glues[0]   = Glue::AND; // replace first glue (null) with AND
			foreach($glues as $i => $glue)
			{
				$filtered = $glue //($glue ?: Glue::AND) // default to AND
					->check($filtered, $collations[$i]);
			}
		}
		$this->filteredIds = $filtered;
		$this->query->setCount(count($this->filteredIds));
		return $this;
	}

	/**
	 * @return CollatorResult|false
	 * @throws ColumnAbsent
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidComparator
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
	 */
	private function checkConditions():CollatorResult|false
	{
		$result = new CollatorResult();
		foreach($this->query->getConditionSet() as $queryCondition)
		{
			$condition = new CollatorCondition($queryCondition);
			if(!$condition->parse()) continue;
			$result->addCollation($this->checkCondition($condition));
			$result->addGlue($condition->getGlue());
		}
		return $result->isEmpty() ? false : $result;
	}

	/**
	 * @param CollatorCondition $condition
	 * @return array
	 * @throws ColumnAbsent
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidComparator
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
	 */
	private function checkCondition(CollatorCondition $condition):array
	{
		$collation = [];
		$column    = $this->columnSet->getColumn($condition->getField());
		foreach($this->unfilteredIds as $id)
		{
			$operator = $condition->getOperator();
			$record   = $this->recordSet->getRecord($id);
			$rValue	  = strtolower($column->getOperableValue($record));
			$cValue   = strtolower($condition->getValue());
			if($operator->check($rValue, $cValue)) $collation[] = $id;
		}
		return $collation;
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
		$recordIds  = $this->filteredIds ?? $this->unfilteredIds;
		$args = [];
		foreach($this->query->getSortArray() as $vector)
		{
			$field 	      = $vector[QueryKey::SORT_FIELD->value];
			$strDirection = $vector[QueryKey::SORT_DIRECTION->value];
			if(empty($field)) continue;
			$direction = SortDirection::tryFrom($strDirection);
			$column = $this->columnSet->getColumn($field);
			$values = [];
			foreach($recordIds as $id)
			{
				$record = $this->recordSet->getRecord($id);
				$values[] = $column->getSortableValue($record);
			}
			$args[] = $values;
			$args[] = $direction->toPhpValue();
			$args[] = SORT_STRING | SORT_NATURAL | SORT_FLAG_CASE;
		}
		$args[] = $recordIds;
		array_multisort(...$args);
		$this->sortedIds = array_pop($args);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function paginate():self
	{
		if(!$this->query->hasLimit()) return $this;
		$limit  = $this->query->getLimit();
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