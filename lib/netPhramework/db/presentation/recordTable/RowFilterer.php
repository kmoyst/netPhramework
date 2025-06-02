<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\Operator;
use netPhramework\db\mapping\SortDirection;

class RowFilterer
{
	private RowFactory $factory;
	private FilterContext $context;

	private array $allIds;
	private array $filteredIds;
	private array $sortedIds;
	private array $paginatedIds;

	/**
	 * Dependency - Filter Context
	 * @param FilterContext $context
	 * @return $this
	 */
	public function setContext(FilterContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function setFactory(RowFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function setAllIds(array $allIds): self
	{
		$this->allIds = $allIds;
		return $this;
	}

	/**
	 * @return $this
	 * @throws Exception
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	public function select():RowFilterer
	{
		$allIds = array_combine($this->allIds, $this->allIds);
		$glues  = [];
		$ids    = [];
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
			$currentConditionIds = $allIds;
			foreach($allIds as $id)
			{
				$recordValue = $this->factory
					->getRow($id)
					->getOperationValue($field);
				if(!$operator->check( // case insensitive
					strtolower($recordValue), strtolower($value)))
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
	public function sort():RowFilterer
	{
		$args = [];
		$ids  = $this->filteredIds ?? $this->allIds;
		foreach($this->context->getSortArray() as $vector)
		{
			$field = $vector[FilterKey::SORT_FIELD->value];
			$direction = $vector[FilterKey::SORT_DIRECTION->value];
			if(empty($field)) break;
			$parsedDirection = SortDirection::tryFrom($direction);
			$values = [];
			foreach($ids as $id)
			{
				$row = $this->factory->getRow($id);
				$values[] = $row->getSortableValue($field);
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
	 * @throws MappingException
	 */
	public function paginate():self
	{
		$limit = $this->context->getLimit();
		$offset = $this->context->getOffset();
		$ids = $this->sortedIds ?? $this->filteredIds ?? $this->allIds;
		$this->paginatedIds = array_slice($ids, $offset, $limit);
		return $this;
	}

	public function getUnpaginatedRowSet():RowSet
	{
		return new RowSet()
			->setFactory($this->factory)
			->setTraversible(
				$this->sortedIds ??
				$this->filteredIds ??
				$this->allIds)
			;
	}

	public function getProcessedRowSet():RowSet
	{
		return new RowSet()
			->setFactory($this->factory)
			->setTraversible(
				$this->paginatedIds ??
				$this->sortedIds ??
				$this->filteredIds ??
				$this->allIds)
			;
	}
}