<?php

namespace netPhramework\db\presentation\recordTable\configuration;

use netPhramework\common\Variables;
use netPhramework\db\presentation\recordTable\filterForm\FilterFormContext;

class FilterContext implements FilterFormContext
{
	private array $conditionSet;
	private array $sortArray;
	private ?int $limit = null;
	private int $offset = 0;
	private int $count;

	public function parse(Variables $vars):self
	{
		$limit = $vars->getOrNull(FilterKey::LIMIT->value);
		$offset = $vars->getOrNull(FilterKey::OFFSET->value) ?? 0;
		$sortArray = $vars->getOrNull(FilterKey::SORT_ARRAY->value) ?? [];
		$conditions = $vars->getOrNull(FilterKey::CONDITION_SET->value) ?? [];
		$this->limit = is_numeric($limit) && $limit > 0 ? $limit : null;
		$this->offset = $offset;
		$this->sortArray = $sortArray;
		$this->conditionSet = $conditions;
		return $this;
	}

	public function getConditionSet(): array
	{
		if(empty($this->conditionSet)) return [];
		$conditionSet = $this->conditionSet;
		$lastCondition = $conditionSet[count($conditionSet)-1];
		if(empty($lastCondition[FilterKey::CONDITION_FIELD->value]) ||
			empty($lastCondition[FilterKey::CONDITION_VALUE->value]))
				array_pop($conditionSet);
		return $conditionSet;
	}

	public function getSortArray(): array
	{
		if(empty($this->sortArray)) return [];
		$sortArray = $this->sortArray;
		if(empty($sortArray[count($sortArray)-1]
		[FilterKey::SORT_FIELD->value])) array_pop($sortArray);
		return $sortArray;
	}

	public function setCount(int $count): self
	{
		$this->count = $count;
		return $this;
	}

	public function getLimit(): ?int
	{
		return $this->limit;
	}

	public function hasLimit():bool
	{
		return $this->limit !== null;
	}

	public function getOffset(): int
	{
		return $this->offset < $this->count ? $this->offset : 0;
	}

	public function getCount(): int
	{
		return $this->count;
	}
}